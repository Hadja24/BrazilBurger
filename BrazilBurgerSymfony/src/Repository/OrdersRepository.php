<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\Customer;
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

    public function findByDeliveryGuyAndStatus($deliveryGuy, $deliveryStatus = null)
    {
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.deliveryGuy = :deliveryGuy')
            ->setParameter('deliveryGuy', $deliveryGuy);

        if ($deliveryStatus) {
            $qb->andWhere('o.deliveryStatus = :deliveryStatus')
                ->setParameter('deliveryStatus', $deliveryStatus);
        }

        return $qb->getQuery()->getResult();
    }

    // Ajouter cette méthode dans src/Repository/OrdersRepository.php
    public function findByCustomer(Customer $customer): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.customer = :customer')
            ->setParameter('customer', $customer)
            ->orderBy('o.orderDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOrdersToDeliverByZone(?int $zoneId = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY)
            ->orderBy('o.orderDate', 'ASC');

        if ($zoneId) {
            $qb->andWhere('o.zone = :zone')
                ->setParameter('zone', $zoneId);
        }

        return $qb->getQuery()->getResult();
    }

    // Dans OrdersRepository.php
    public function findDeliveryOrdersWithFilters(?int $zoneId = null, ?string $deliveryStatus = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.customer', 'c')
            ->leftJoin('c.account', 'a')
            ->leftJoin('o.zone', 'z')
            ->leftJoin('o.deliveryGuy', 'dg')
            ->leftJoin('dg.account', 'dga')
            ->where('o.receptionType = :delivery')
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY);

        if ($zoneId) {
            $qb->andWhere('o.zone = :zone')
                ->setParameter('zone', $zoneId);
        }

        if ($deliveryStatus) {
            $qb->andWhere('o.deliveryStatus = :status')
                ->setParameter('status', $deliveryStatus);
        }

        return $qb->addOrderBy('o.orderDate', 'ASC')->getQuery()->getResult();
    }
    // Ajouter ces méthodes dans src/Repository/OrdersRepository.php

    public function findDeliveryOrders(?int $zoneId = null, ?string $deliveryStatus = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY)
            ->orderBy('o.orderDate', 'DESC');

        if ($zoneId) {
            $qb->andWhere('o.zone = :zone')
                ->setParameter('zone', $zoneId);
        }

        if ($deliveryStatus) {
            // Version tolérante : cherche les variations possibles
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->eq('o.deliveryStatus', ':status'),
                $qb->expr()->eq('LOWER(o.deliveryStatus)', 'LOWER(:status)'),
                $qb->expr()->like('LOWER(o.deliveryStatus)', $qb->expr()->literal('%' . strtolower($deliveryStatus) . '%'))
            ))
            ->setParameter('status', $deliveryStatus);
        }

        return $qb->getQuery()->getResult();
    }

    public function findDeliveryOrdersWithCustomers(?int $zoneId = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.customer', 'c')
            ->leftJoin('c.account', 'a')
            ->addSelect('c', 'a') // Important : inclure dans le SELECT
            ->where('o.receptionType = :delivery')
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY);

        if ($zoneId) {
            $qb->andWhere('o.zone = :zone')
                ->setParameter('zone', $zoneId);
        }

        return $qb->getQuery()->getResult();
    }

    public function getDeliveryGuyStats(int $deliveryGuyId, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->select([
                'COUNT(o.id) as total_deliveries',
                'SUM(CASE WHEN o.deliveryStatus = :delivered THEN 1 ELSE 0 END) as delivered',
                'SUM(CASE WHEN o.deliveryStatus = :ongoing THEN 1 ELSE 0 END) as ongoing',
                'SUM(CASE WHEN o.deliveryStatus = :pending THEN 1 ELSE 0 END) as pending',
                'AVG(o.deliveryRating) as avg_rating'
            ])
            ->andWhere('o.deliveryGuy = :deliveryGuy')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('deliveryGuy', $deliveryGuyId)
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY)
            ->setParameter('delivered', Orders::DELIVERY_STATUS_DELIVERED)
            ->setParameter('ongoing', Orders::DELIVERY_STATUS_ONGOING)
            ->setParameter('pending', Orders::DELIVERY_STATUS_PENDING);

        if ($status) {
            $qb->andWhere('o.deliveryStatus = :status')
            ->setParameter('status', $status);
        }

        return $qb->getQuery()->getSingleResult();
    }

    public function getDeliveryGuyDetailedStats(int $deliveryGuyId): array
    {
        $qb = $this->createQueryBuilder('o')
            ->select([
                'COUNT(o.id) as total_deliveries',
                'SUM(CASE WHEN o.deliveryStatus = :delivered THEN 1 ELSE 0 END) as delivered',
                'SUM(CASE WHEN o.deliveryStatus = :ongoing THEN 1 ELSE 0 END) as ongoing',
                'SUM(CASE WHEN o.deliveryStatus = :pending THEN 1 ELSE 0 END) as pending',
                'SUM(CASE WHEN o.deliveryStatus = :cancelled THEN 1 ELSE 0 END) as cancelled',
                'AVG(o.deliveryRating) as avg_rating',
                'MIN(o.deliveryRating) as min_rating',
                'MAX(o.deliveryRating) as max_rating',
                'SUM(o.totalPrice) as total_revenue'
            ])
            ->andWhere('o.deliveryGuy = :deliveryGuy')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('deliveryGuy', $deliveryGuyId)
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY)
            ->setParameter('delivered', Orders::DELIVERY_STATUS_DELIVERED)
            ->setParameter('ongoing', Orders::DELIVERY_STATUS_ONGOING)
            ->setParameter('pending', Orders::DELIVERY_STATUS_PENDING)
            ->setParameter('cancelled', Orders::DELIVERY_STATUS_CANCELLED);

        return $qb->getQuery()->getSingleResult();
    }

    public function findRecentDeliveriesByDeliveryGuy(int $deliveryGuyId, int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.deliveryGuy = :deliveryGuy')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('deliveryGuy', $deliveryGuyId)
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY)
            ->orderBy('o.orderDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findDeliveriesByDeliveryGuyAndStatus(int $deliveryGuyId, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.deliveryGuy = :deliveryGuy')
            ->andWhere('o.receptionType = :delivery')
            ->setParameter('deliveryGuy', $deliveryGuyId)
            ->setParameter('delivery', Orders::RECEPTION_DELIVERY)
            ->orderBy('o.orderDate', 'DESC');

        if ($status) {
            $qb->andWhere('o.deliveryStatus = :status')
               ->setParameter('status', $status);
        }

        return $qb->getQuery()->getResult();
    }
}