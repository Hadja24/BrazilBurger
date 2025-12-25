<?php

namespace App\Repository;
use App\Entity\Orders;
use App\Entity\OrderBurger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderBurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderBurger::class);
    }


    public function findTopSoldBurgers(int $limit = 5): array
    {
        return $this->createQueryBuilder('ob')
            ->leftJoin('ob.burger', 'b')
            ->leftJoin('ob.order', 'o')
            ->select('b.name as burgerName', 
                    'SUM(ob.quantity) as totalSold',
                    'b.price',
                    'SUM(ob.quantity * b.price) as revenue')
            ->where('o.orderState = :finished')
            ->setParameter('finished', 'FINISHED')
            ->groupBy('ob.burger')
            ->orderBy('totalSold', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByOrder(Orders $order): array
    {
        return $this->createQueryBuilder('ob')
            ->andWhere('ob.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getResult();
    }
}
