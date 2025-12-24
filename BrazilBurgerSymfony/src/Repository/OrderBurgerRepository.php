<?php

namespace App\Repository;

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
            ->select('b.name as name', 'SUM(ob.quantity) as totalQuantity')
            ->join('ob.burger', 'b')
            ->groupBy('b.id')
            ->orderBy('totalQuantity', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}