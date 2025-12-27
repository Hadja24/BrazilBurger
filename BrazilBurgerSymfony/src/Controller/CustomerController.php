<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Customer;
use App\Repository\OrdersRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customers')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_customers_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_customers_show', methods: ['GET'])]
    public function show(Customer $customer, OrdersRepository $ordersRepository): Response
    {
        // Récupérer toutes les commandes du client
        $orders = $ordersRepository->findByCustomer($customer);
        
        // Calculer les statistiques
        $stats = [
            'total_orders' => count($orders),
            'total_spent' => array_sum(array_map(fn($order) => $order->getTotalPrice(), $orders)),
            'pending_orders' => count(array_filter($orders, fn($order) => $order->getOrderState() === 'PENDING')),
            'finished_orders' => count(array_filter($orders, fn($order) => $order->getOrderState() === 'FINISHED')),
        ];
        
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
            'orders' => $orders,
            'stats' => $stats,
        ]);
    }

    #[Route('/search', name: 'app_customers_search', methods: ['GET', 'POST'])]
    public function search(Request $request, CustomerRepository $customerRepository): Response
    {
        $name = $request->query->get('name') ?? $request->request->get('name');
        $surname = $request->query->get('surname') ?? $request->request->get('surname');
        $phone = $request->query->get('phone') ?? $request->request->get('phone');
        
        $customer = $customerRepository->findOneByFuzzySearch($name, $surname, $phone);
        
        if ($customer) {
            return $this->redirectToRoute('app_customers_show', ['id' => $customer->getId()]);
        }
        
        $this->addFlash('error', 'Aucun client trouvé avec ces critères.');
        return $this->redirectToRoute('app_customers_index');
    }

    #[Route('/{customerId}/cancel-order/{orderId}', name: 'app_customers_cancel_order', methods: ['POST'])]
    public function cancelOrder(
        int $customerId,
        int $orderId,
        OrdersRepository $ordersRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $order = $ordersRepository->find($orderId);
        
        if (!$order) {
            $this->addFlash('error', 'Commande non trouvée.');
            return $this->redirectToRoute('app_customers_show', ['id' => $customerId]);
        }
        
        // Vérifier que la commande appartient bien au client
        if ($order->getCustomer()->getId() !== $customerId) {
            $this->addFlash('error', 'Cette commande n\'appartient pas à ce client.');
            return $this->redirectToRoute('app_customers_show', ['id' => $customerId]);
        }
        
        // Vérifier le token CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('cancel' . $order->getId(), $token)) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_customers_show', ['id' => $customerId]);
        }
        
        // Vérifier si la commande peut être annulée (seulement si elle est en attente)
        if ($order->getOrderState() === Orders::STATE_PENDING) {
            $order->setOrderState(Orders::STATE_CANCELLED);
            
            // Si c'est une livraison, mettre à jour le statut de livraison aussi
            if ($order->getReceptionType() === Orders::RECEPTION_DELIVERY) {
                $order->setDeliveryStatus(Orders::DELIVERY_STATUS_CANCELLED);
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Commande annulée avec succès.');
        } else {
            $this->addFlash('error', 'Cette commande ne peut pas être annulée (déjà terminée ou annulée).');
        }
        
        return $this->redirectToRoute('app_customers_show', ['id' => $customerId]);
    }

    #[Route('/orders', name: 'app_customer_orders', methods: ['GET', 'POST'])]
    public function listCustomerOrders(
        Request $request,
        OrdersRepository $ordersRepository,
        CustomerRepository $customerRepository
    ): Response {
        $name = $request->query->get('name');
        $surname = $request->query->get('surname');
        $phone = $request->query->get('phone');
        
        $orders = [];
        $customer = null;

        // Si des critères de recherche sont fournis
        if ($name || $surname || $phone) {
            // Chercher le client
            $customer = $customerRepository->findOneByFuzzySearch($name, $surname, $phone);

            if ($customer) {
                // Récupérer toutes les commandes de ce client
                $orders = $ordersRepository->findByCustomer($customer);
            }
        }

        return $this->render('customer/orders.html.twig', [
            'customer' => $customer,
            'orders' => $orders,
            'name' => $name,
            'surname' => $surname,
            'phone' => $phone,
        ]);
    }
}