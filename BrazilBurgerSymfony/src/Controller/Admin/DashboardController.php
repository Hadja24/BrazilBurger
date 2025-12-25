<?php

namespace App\Controller\Admin;

use App\Repository\OrdersRepository;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(
        OrdersRepository $ordersRepository,
        AccountRepository $accountRepository
    ): Response
    {
        $today = new \DateTimeImmutable('today');
        $tomorrow = new \DateTimeImmutable('tomorrow');

        return $this->render('admin/dashboard/index.html.twig', [
            'dailyRevenue' => $ordersRepository->findTotalRevenueByPeriod($today, $tomorrow),
            'totalRevenue' => $ordersRepository->getTotalRevenue(),
            'totalOrders' => $ordersRepository->getTotalOrdersCount(),
            'dailyOrders' => $ordersRepository->countDailyOrders($today),
            'pendingOrders' => $ordersRepository->getOrdersByStatus('PENDING'),
            'finishedOrders' => $ordersRepository->getOrdersByStatus('FINISHED'),
            'cancelledOrders' => $ordersRepository->getOrdersByStatus('CANCELLED'),
            'totalCustomers' => $accountRepository->count(['role' => 'CUSTOMER']),
            'recentOrders' => $ordersRepository->findRecentOrders(5), // Version simplifiée
        ]);
    }
}