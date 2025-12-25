<?php

namespace App\Controller\Admin; // Namespace mis à jour pour le sous-dossier Admin

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Repository\OrderBurgerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class DashboardController extends AbstractController
{
    // Injection de dépendances via le constructeur (SOLID : DIP)
    public function __construct(
        private OrdersRepository $ordersRepo,
        private OrderBurgerRepository $orderBurgerRepo
    ) {}
    /*
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        $today = new \DateTimeImmutable('today');
        $tomorrow = new \DateTimeImmutable('tomorrow');

        return $this->render('admin/dashboard/index.html.twig', [
            // On utilise les constantes de l'entité et les valeurs ENUM de ta base
            'dailyRevenue'    => $this->ordersRepo->findTotalRevenueByPeriod($today, $tomorrow),
            
            // On remplace 'EN_COURS' par 'PENDING' et 'VALIDEE' par 'FINISHED' pour coller à ton SQL
            'pendingOrders'   => $this->ordersRepo->countByStatus(Orders::STATE_PENDING, $today),
            'finishedOrders'  => $this->ordersRepo->countByStatus(Orders::STATE_FINISHED, $today),
            'cancelledOrders' => $this->ordersRepo->countByStatus(Orders::STATE_CANCELLED, $today),
            
            'topBurgers'      => $this->orderBurgerRepo->findTopSoldBurgers(5),
        ]);
    }*/
    public function index(): Response
{
    // On commente tout et on envoie des valeurs à zéro
    return $this->render('admin/dashboard/index.html.twig', [
        'dailyRevenue'    => 0,
        'pendingOrders'   => 0,
        'finishedOrders'  => 0,
        'cancelledOrders' => 0,
        'topBurgers'      => [], // Tableau vide pour tester
    ]);
}
}