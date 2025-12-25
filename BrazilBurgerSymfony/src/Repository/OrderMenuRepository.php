<?php

namespace App\Repository;

use App\Entity\OrderMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderMenu>
 */
class OrderMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderMenu::class);
    }

    public function findTopMenus(int $limit = 5): array
{
    return $this->createQueryBuilder('om')
        ->leftJoin('om.menu', 'm')
        ->leftJoin('om.order', 'o')
        ->select('m.name as menuName', 
                 'SUM(om.quantity) as totalSold',
                 'm.price',
                 'SUM(om.quantity * m.price) as revenue')
        ->where('o.orderState = :finished')
        ->setParameter('finished', 'FINISHED')
        ->groupBy('om.menu')
        ->orderBy('totalSold', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}

    //    /**
    //     * @return OrderMenu[] Returns an array of OrderMenu objects
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

    //    public function findOneBySomeField($value): ?OrderMenu
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
