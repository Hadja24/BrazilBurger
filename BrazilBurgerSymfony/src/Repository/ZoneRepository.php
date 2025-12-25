<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zone>
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    public function findMostPopularZones(int $limit = 4): array
{
    return $this->createQueryBuilder('z')
        ->leftJoin('z.orders', 'o')
        ->select('z.name as zoneName', 
                 'COUNT(o.id) as orderCount',
                 'SUM(o.totalPrice) as totalRevenue')
        ->where('o.orderState = :finished')
        ->setParameter('finished', 'FINISHED')
        ->groupBy('z.id')
        ->orderBy('orderCount', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}
    //    /**
    //     * @return Zone[] Returns an array of Zone objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('z')
    //            ->andWhere('z.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('z.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Zone
    //    {
    //        return $this->createQueryBuilder('z')
    //            ->andWhere('z.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
