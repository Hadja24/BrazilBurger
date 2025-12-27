<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrdersType;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/orders')]
final class OrdersController extends AbstractController
{
    #[Route(name: 'app_orders_index', methods: ['GET'])]
    public function index(OrdersRepository $ordersRepository, Request $request): Response
    {
        // Récupérer les paramètres de filtrage
        $state = $request->query->get('state');
        $type = $request->query->get('type');

        // Filtrer les commandes
        $orders = $ordersRepository->findByFilters($state, $type);
        return $this->render('orders/index.html.twig', [
            'orders' => $orders,
            'current_state' => $state,
            'current_type' => $type,
        ]);
    }

    #[Route('/new', name: 'app_orders_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Commande créée avec succès!');
            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('orders/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_orders_show', methods: ['GET'])]
    public function show(Orders $order): Response
    {
        return $this->render('orders/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_orders_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Commande mise à jour avec succès!');
            return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/update-state', name: 'app_orders_update_state', methods: ['POST'])]
    public function updateState(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        $newState = $request->request->get('state');
        
        // Validation de l'état
        $validStates = ['PENDING', 'CANCELLED', 'FINISHED'];
        if (in_array($newState, $validStates)) {
            $order->setOrderState($newState);
            $entityManager->flush();
            
            $this->addFlash('success', "État de la commande changé à: $newState");
        } else {
            $this->addFlash('error', 'État invalide');
        }

        return $this->redirectToRoute('app_orders_show', ['id' => $order->getId()]);
    }

    #[Route('/{id}', name: 'app_orders_delete', methods: ['POST'])]
    public function delete(Request $request, Orders $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
            $this->addFlash('success', 'Commande supprimée avec succès!');
        }

        return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/mark-delivered', name: 'app_orders_mark_delivered', methods: ['POST'])]
    public function markAsDelivered(Orders $order, EntityManagerInterface $entityManager): JsonResponse
    {
        $order->setDeliveryStatus(Orders::DELIVERY_STATUS_DELIVERED);
        $order->setDeliveryCompletedAt(new \DateTimeImmutable());
        $entityManager->flush();
        
        return $this->json([
            'success' => true,
            'message' => 'Commande marquée comme livrée'
        ]);
    }
}