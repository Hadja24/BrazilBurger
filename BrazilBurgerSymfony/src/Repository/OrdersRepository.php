<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function findByFilters(?string $state = null, ?string $type = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->orderBy('o.orderDate', 'DESC');

        // Filtrage par état
        if ($state && $state !== 'all') {
            $qb->andWhere('o.orderState = :state')
            ->setParameter('state', $state);
        }

        // Filtrage par type de réception
        if ($type && $type !== 'all') {
            $qb->andWhere('o.receptionType = :type')
            ->setParameter('type', $type);
        }

        return $qb->getQuery()->getResult();
    }



    /**
     * Calculer le revenu total sur une période
     */
    public function findTotalRevenueByPeriod(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null): float
    {
        $qb = $this->createQueryBuilder('o')
            ->select('SUM(o.totalPrice) as total')
            ->where('o.orderState = :state')
            ->setParameter('state', 'FINISHED');

        if ($start !== null) {
            $qb->andWhere('o.orderDate >= :start')
               ->setParameter('start', $start);
        }

        if ($end !== null) {
            $qb->andWhere('o.orderDate <= :end')
               ->setParameter('end', $end);
        }

        $result = $qb->getQuery()->getSingleScalarResult();
        return $result ? (float)$result : 0.0;
    }

    /**
     * Commandes récentes - VERSION SIMPLIFIÉE
     */
    public function findRecentOrders(int $limit = 5): array
    {
        return $this->createQueryBuilder('o')
            ->select('o.id', 'o.totalPrice', 'o.orderDate', 'o.orderState')
            ->orderBy('o.orderDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Version avec jointure SQL brute (si tu veux absolument les infos client)
     */
    public function findRecentOrdersWithCustomer(int $limit = 5): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT o.id, o.total_price, o.order_date, o.order_state,
                   a.name, a.surname
            FROM orders o
            LEFT JOIN customer c ON o.customer_id = c."Id"  -- Note: "Id" avec majuscule
            LEFT JOIN account a ON c.account_id = a.id
            ORDER BY o.order_date DESC
            LIMIT :limit
        ';
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * Nombre total de commandes
     */
    public function getTotalOrdersCount(): int
    {
        return $this->count([]);
    }
    
    /**
     * Nombre de commandes par statut
     */
    public function getOrdersByStatus(string $status): int
    {
        return $this->count(['orderState' => $status]);
    }
    
    /**
     * Revenu total
     */
    public function getTotalRevenue(): float
    {
        return $this->findTotalRevenueByPeriod();
    }
    
    /**
     * Compter les commandes du jour
     */
    public function countDailyOrders(\DateTimeImmutable $date): int
    {
        $startOfDay = $date->setTime(0, 0, 0);
        $endOfDay = $date->setTime(23, 59, 59);
        
        return $this->count([
            'orderDate' => [
                'start' => $startOfDay,
                'end' => $endOfDay
            ],
            'orderState' => 'FINISHED'
        ]);
    }
}