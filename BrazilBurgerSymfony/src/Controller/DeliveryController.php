<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\DeliveryGuy;
use App\Form\AssignDeliveryGuyType;
use App\Repository\DeliveryGuyRepository;
use App\Repository\OrdersRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/delivery')]
class DeliveryController extends AbstractController
{
    // Page d'accueil des livraisons : liste des commandes à livrer par zone
    #[Route('/', name: 'app_delivery_index', methods: ['GET'])]
    public function index(
        Request $request,
        OrdersRepository $ordersRepository,
        ZoneRepository $zoneRepository,
    ): Response {
        $rawZoneId = $request->query->get('zone');
        $zoneId = $rawZoneId !== null && $rawZoneId !== '' ? (int)$rawZoneId : null;
        $deliveryStatus = $request->query->get('status'); // Ajoutez cette ligne

        // Conversion de zoneId
        $zoneId = null;
        if ($rawZoneId !== null && $rawZoneId !== '') {
            $zoneId = filter_var($rawZoneId, FILTER_VALIDATE_INT);
            if ($zoneId === false) {
                $zoneId = null;
                $this->addFlash('warning', 'ID de zone invalide, affichage de toutes les zones');
            }
        }
        $zones = $zoneRepository->findAll();

        $orders = $ordersRepository->findDeliveryOrders($zoneId, $deliveryStatus);
     
    


        if (count($orders) === 0) {
                    $this->addFlash('info', 'Aucune commande de livraison trouvée. Vérifiez que des commandes existent avec reception_type = "DELIVERY".');
                }

                return $this->render('delivery/index.html.twig', [
                    'orders' => $orders,
                    'zones' => $zones,
                    'selectedZone' => $zoneId,
                    'selectedStatus' => $deliveryStatus,
                    'deliveryStatuses' => Orders::getDeliveryStatuses(),
                ]);
        }

    // Affecter un livreur à une commande
    #[Route('/order/{id}/assign', name: 'app_delivery_assign', methods: ['GET', 'POST'])]
    public function assignDeliveryGuy(
        Request $request,
        Orders $order,
        EntityManagerInterface $entityManager,
        DeliveryGuyRepository $deliveryGuyRepository
    ): Response {
        // Vérifier que la commande est bien de type livraison et en attente
        if ($order->getReceptionType() !== Orders::RECEPTION_DELIVERY) {
            $this->addFlash('error', 'Cette commande n\'est pas une livraison.');
            return $this->redirectToRoute('app_delivery_index');
        }

        $form = $this->createForm(AssignDeliveryGuyType::class, $order, [
            'delivery_guys' => $deliveryGuyRepository->findAll(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour le statut de la livraison
            $order->setDeliveryStatus(Orders::DELIVERY_STATUS_ONGOING);
            $entityManager->flush();

            $this->addFlash('success', 'Livreur affecté avec succès.');
            return $this->redirectToRoute('app_delivery_index');
        }

        return $this->render('delivery/assign.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    // Liste des livreurs avec leurs états de livraison
    // Liste des livreurs avec leurs états de livraison
    #[Route('/delivery-guys', name: 'app_delivery_delivery_guys', methods: ['GET'])]
    public function listDeliveryGuys(
        Request $request, // Ajoutez Request en paramètre
        DeliveryGuyRepository $deliveryGuyRepository,
        OrdersRepository $ordersRepository
    ): Response {
        $status = $request->query->get('status'); // Récupérez le paramètre status
        
        $deliveryGuys = $deliveryGuyRepository->findAll();
        $deliveryGuysWithStats = [];

        foreach ($deliveryGuys as $deliveryGuy) {
            // Utilisez la méthode avec le paramètre status si fourni
            $deliveries = $ordersRepository->findDeliveriesByDeliveryGuyAndStatus($deliveryGuy->getId(), $status);
             // Calculer la note moyenne
            $averageRating = 0;
            $ratedDeliveries = array_filter($deliveries, function($order) {
                return $order->getDeliveryRating() !== null;
            });
            
            if (count($ratedDeliveries) > 0) {
                $averageRating = array_sum(array_map(function($order) {
                    return $order->getDeliveryRating();
                }, $ratedDeliveries)) / count($ratedDeliveries);
            }
            $deliveryGuysWithStats[] = [
                'deliveryGuy' => $deliveryGuy,
                'totalDeliveries' => count($deliveries),
                'pendingDeliveries' => count(array_filter($deliveries, function($order) {
                    return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_PENDING;
                })),
                'ongoingDeliveries' => count(array_filter($deliveries, function($order) {
                    return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_ONGOING;
                })),
                'deliveredDeliveries' => count(array_filter($deliveries, function($order) {
                    return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_DELIVERED;
                })),
                'averageRating' => $averageRating,
            ];
        }

        return $this->render('delivery/delivery_guys.html.twig', [
            'deliveryGuys' => $deliveryGuysWithStats,
            'status' => $status, // Passez le statut sélectionné
            'deliveryStatuses' => Orders::getDeliveryStatuses(), // AJOUTEZ CETTE LIGNE
        ]);
    }

    // Détails d'un livreur (performances)
    #[Route('/delivery-guy/{id}', name: 'app_delivery_delivery_guy_show', methods: ['GET'])]
    public function showDeliveryGuy(
        DeliveryGuy $deliveryGuy,
        OrdersRepository $ordersRepository
    ): Response {
        $deliveries = $ordersRepository->findDeliveriesByDeliveryGuyAndStatus($deliveryGuy->getId(), null);

         // Récupérer les 10 dernières livraisons
        $recentDeliveries = array_slice($deliveries, 0, 10);

        // Calculer quelques statistiques
        $totalDeliveries = count($deliveries);
        $deliveredDeliveries = count(array_filter($deliveries, function($order) {
            return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_DELIVERED;
        }));
        $ongoingDeliveries = count(array_filter($deliveries, function($order) {
        return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_ONGOING;
        }));
        $pendingDeliveries = count(array_filter($deliveries, function($order) {
            return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_PENDING;
        }));
        $cancelledDeliveries = count(array_filter($deliveries, function($order) {
            return $order->getDeliveryStatus() === Orders::DELIVERY_STATUS_CANCELLED;
        }));

        $averageRating = 0;
        $minRating = null;
        $maxRating = null;
        $ratedDeliveries = array_filter($deliveries, function($order) {
            return $order->getDeliveryRating() !== null;
        });
        if (count($ratedDeliveries) > 0) {
            $averageRating = array_sum(array_map(function($order) {
                return $order->getDeliveryRating();
            }, $ratedDeliveries)) / count($ratedDeliveries);
        }

        // Calcul du revenu total
        $totalRevenue = 0;
        foreach ($deliveries as $delivery) {
            $totalRevenue += $delivery->getTotalPrice();
        }
       
    // Construire le tableau stats
        $stats = [
            'total_deliveries' => $totalDeliveries,
            'delivered' => $deliveredDeliveries,
            'ongoing' => $ongoingDeliveries,
            'pending' => $pendingDeliveries,
            'cancelled' => $cancelledDeliveries,
            'avg_rating' => $averageRating,
            'min_rating' => $minRating,   // Ajoutez cette ligne
            'max_rating' => $maxRating,   // Ajoutez cette ligne
            'total_revenue' => $totalRevenue,
        ];

        return $this->render('delivery/show_delivery_guy.html.twig', [
            'deliveryGuy' => $deliveryGuy,
            'deliveries' => $deliveries,
            'recentDeliveries' => $recentDeliveries, // Les 10 dernières livraisons
            'stats' => $stats, // On envoie le tableau stats
            'totalDeliveries' => $totalDeliveries,
            'deliveredDeliveries' => $deliveredDeliveries,
            'averageRating' => $averageRating,
        ]);
    }
}