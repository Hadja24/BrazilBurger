<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }
    
    /**
     * SRP: Cette méthode ne s'occupe que de la somme des recettes d'une période
     */
    public function findTotalRevenueByPeriod(\DateTimeInterface $start, \DateTimeInterface $end): float
{
    return (float) $this->createQueryBuilder('o')
        ->select('SUM(o.totalPrice)')
        ->where('o.orderDate BETWEEN :start AND :end')
        // On ne compte que les commandes terminées pour la recette
        ->andWhere('o.orderState = :finished') 
        ->setParameter('start', $start)
        ->setParameter('end', $end)
        ->setParameter('finished', Orders::STATE_FINISHED)
        ->getQuery()
        ->getSingleScalarResult();
}

    /**
     * SRP: Compte les commandes selon un état précis
     */
    public function countByStatus(string $status, \DateTimeInterface $date): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.orderState = :status')
            ->andWhere('o.orderDate >= :date')
            ->setParameter('status', $status)
            ->setParameter('date', $date->format('Y-m-d 00:00:00'))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
//    /**
//     * @return Orders[] Returns an array of Orders objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Orders
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

