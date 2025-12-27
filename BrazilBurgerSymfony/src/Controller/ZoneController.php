<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Entity\Neighbourhood;
use App\Entity\DeliveryGuy;
use App\Form\ZoneType;
use App\Form\NeighbourhoodType;
use App\Repository\ZoneRepository;
use App\Repository\OrdersRepository;
use App\Repository\DeliveryGuyRepository;
use App\Repository\NeighbourhoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/zones')]
class ZoneController extends AbstractController
{
    #[Route('/', name: 'app_zones_index', methods: ['GET'])]
    public function index(ZoneRepository $zoneRepository, OrdersRepository $ordersRepository): Response
    {
        $zones = $zoneRepository->findAll();
        
        // Calculer les statistiques pour chaque zone
        $zonesWithStats = [];
        foreach ($zones as $zone) {
            $zoneOrders = $ordersRepository->createQueryBuilder('o')
                ->andWhere('o.zone = :zone')
                ->andWhere('o.receptionType = :delivery')
                ->setParameter('zone', $zone)
                ->setParameter('delivery', 'DELIVERY')
                ->getQuery()
                ->getResult();
            
            $deliveredOrders = array_filter($zoneOrders, fn($order) => 
                $order->getDeliveryStatus() === 'delivered'
            );
            
            $zonesWithStats[] = [
                'zone' => $zone,
                'total_orders' => count($zoneOrders),
                'delivered_orders' => count($deliveredOrders),
                'total_revenue' => array_sum(array_map(fn($order) => $order->getTotalPrice(), $zoneOrders)),
                'neighbourhood_count' => $zone->getNeighbourhoods()->count(),
            ];
        }
        
        return $this->render('zone/index.html.twig', [
            'zones' => $zonesWithStats,
        ]);
    }

    #[Route('/new', name: 'app_zones_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zone);
            $entityManager->flush();

            $this->addFlash('success', 'Zone créée avec succès!');
            return $this->redirectToRoute('app_zones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('zone/new.html.twig', [
            'zone' => $zone,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_zones_show', methods: ['GET'])]
    public function show(
        Zone $zone, 
        OrdersRepository $ordersRepository,
        DeliveryGuyRepository $deliveryGuyRepository
    ): Response
    {
        // Récupérer les commandes de livraison pour cette zone
        $zoneOrders = $ordersRepository->createQueryBuilder('o')
            ->andWhere('o.zone = :zone')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('zone', $zone)
            ->setParameter('delivery', 'DELIVERY')
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
        
        // Calculer les statistiques
        $stats = [
            'total_orders' => count($zoneOrders),
            'delivered_orders' => count(array_filter($zoneOrders, fn($order) => 
                $order->getDeliveryStatus() === 'delivered'
            )),
            'pending_orders' => count(array_filter($zoneOrders, fn($order) => 
                $order->getDeliveryStatus() === 'pending'
            )),
            'ongoing_orders' => count(array_filter($zoneOrders, fn($order) => 
                $order->getDeliveryStatus() === 'ongoing'
            )),
            'total_revenue' => array_sum(array_map(fn($order) => $order->getTotalPrice(), $zoneOrders)),
        ];
        
        // Récupérer les livreurs actifs (ceux qui ont livré dans cette zone)
        $deliveryGuys = $deliveryGuyRepository->createQueryBuilder('dg')
            ->innerJoin('dg.deliveries', 'o')
            ->andWhere('o.zone = :zone')
            ->setParameter('zone', $zone)
            ->groupBy('dg.id')
            ->getQuery()
            ->getResult();
        
        // Calculer les stats par livreur
        $deliveryGuysWithStats = [];
        foreach ($deliveryGuys as $deliveryGuy) {
            $deliveries = $ordersRepository->createQueryBuilder('o')
                ->andWhere('o.deliveryGuy = :deliveryGuy')
                ->andWhere('o.zone = :zone')
                ->setParameter('deliveryGuy', $deliveryGuy)
                ->setParameter('zone', $zone)
                ->getQuery()
                ->getResult();
            
            $deliveryGuysWithStats[] = [
                'deliveryGuy' => $deliveryGuy,
                'total_deliveries' => count($deliveries),
                'delivered_deliveries' => count(array_filter($deliveries, fn($order) => 
                    $order->getDeliveryStatus() === 'delivered'
                )),
                'average_rating' => count($deliveries) > 0 ? 
                    array_sum(array_map(fn($order) => $order->getDeliveryRating() ?? 0, $deliveries)) / count($deliveries) : 0,
            ];
        }
        
        return $this->render('zone/show.html.twig', [
            'zone' => $zone,
            'orders' => array_slice($zoneOrders, 0, 10), // 10 dernières commandes
            'neighbourhoods' => $zone->getNeighbourhoods(),
            'stats' => $stats,
            'deliveryGuys' => $deliveryGuysWithStats,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_zones_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Zone $zone, 
        EntityManagerInterface $entityManager,
        NeighbourhoodRepository $neighbourhoodRepository,
        DeliveryGuyRepository $deliveryGuyRepository
    ): Response
    {
        // Formulaire pour modifier la zone
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);
        
        // Formulaire pour ajouter un quartier
        $neighbourhood = new Neighbourhood();
        $neighbourhood->setZone($zone);
        $neighbourhoodForm = $this->createForm(NeighbourhoodType::class, $neighbourhood);
        $neighbourhoodForm->handleRequest($request);
        
        // Liste des livreurs disponibles
        $availableDeliveryGuys = $deliveryGuyRepository->findAll();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Zone modifiée avec succès!');
            return $this->redirectToRoute('app_zones_show', ['id' => $zone->getId()]);
        }
        
        if ($neighbourhoodForm->isSubmitted() && $neighbourhoodForm->isValid()) {
            $entityManager->persist($neighbourhood);
            $entityManager->flush();
            $this->addFlash('success', 'Quartier ajouté avec succès!');
            return $this->redirectToRoute('app_zones_edit', ['id' => $zone->getId()]);
        }

        return $this->render('zone/edit.html.twig', [
            'zone' => $zone,
            'form' => $form->createView(),
            'neighbourhoodForm' => $neighbourhoodForm->createView(),
            'neighbourhoods' => $zone->getNeighbourhoods(),
            'availableDeliveryGuys' => $availableDeliveryGuys,
        ]);
    }

    #[Route('/{zoneId}/delete-neighbourhood/{neighbourhoodId}', name: 'app_zones_delete_neighbourhood', methods: ['POST'])]
    public function deleteNeighbourhood(
        int $zoneId,
        int $neighbourhoodId,
        EntityManagerInterface $entityManager,
        NeighbourhoodRepository $neighbourhoodRepository,
        Request $request
    ): Response
    {
        $neighbourhood = $neighbourhoodRepository->find($neighbourhoodId);
        
        if (!$neighbourhood || $neighbourhood->getZone()->getId() !== $zoneId) {
            $this->addFlash('error', 'Quartier non trouvé ou n\'appartient pas à cette zone.');
            return $this->redirectToRoute('app_zones_edit', ['id' => $zoneId]);
        }
        
        // Vérifier le token CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_neighbourhood' . $neighbourhoodId, $token)) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_zones_edit', ['id' => $zoneId]);
        }
        
        $entityManager->remove($neighbourhood);
        $entityManager->flush();
        
        $this->addFlash('success', 'Quartier supprimé avec succès.');
        return $this->redirectToRoute('app_zones_edit', ['id' => $zoneId]);
    }

    #[Route('/{zoneId}/assign-deliveryguy/{deliveryGuyId}', name: 'app_zones_assign_deliveryguy', methods: ['POST'])]
    public function assignDeliveryGuy(
        int $zoneId,
        int $deliveryGuyId,
        EntityManagerInterface $entityManager,
        ZoneRepository $zoneRepository,
        DeliveryGuyRepository $deliveryGuyRepository,
        Request $request
    ): Response
    {
        $zone = $zoneRepository->find($zoneId);
        $deliveryGuy = $deliveryGuyRepository->find($deliveryGuyId);
        
        if (!$zone || !$deliveryGuy) {
            $this->addFlash('error', 'Zone ou livreur non trouvé.');
            return $this->redirectToRoute('app_zones_edit', ['id' => $zoneId]);
        }
        
        // Vérifier le token CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('assign_deliveryguy' . $deliveryGuyId, $token)) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_zones_edit', ['id' => $zoneId]);
        }
        
        // Note: Dans votre entité Zone, il n'y a pas de relation directe avec DeliveryGuy
        // Cette fonctionnalité nécessiterait d'ajouter une relation ManyToMany entre Zone et DeliveryGuy
        // Pour l'instant, on va juste afficher un message
        
        $this->addFlash('info', 'La fonctionnalité d\'affectation de livreurs nécessite une modification de l\'entité Zone.');
        return $this->redirectToRoute('app_zones_edit', ['id' => $zoneId]);
    }

    #[Route('/{id}', name: 'app_zones_delete', methods: ['POST'])]
    public function delete(Request $request, Zone $zone, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zone->getId(), $request->request->getString('_token'))) {
            $entityManager->remove($zone);
            $entityManager->flush();
            
            $this->addFlash('success', 'Zone supprimée avec succès!');
        }

        return $this->redirectToRoute('app_zones_index', [], Response::HTTP_SEE_OTHER);
    }
}