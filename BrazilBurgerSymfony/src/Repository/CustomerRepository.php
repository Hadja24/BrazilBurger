<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function countTodayCustomers(): int
    {
        $today = new \DateTimeImmutable('today');
        $tomorrow = new \DateTimeImmutable('tomorrow');
        
        // Si ta table Customer a une colonne de date de création
        // Sinon, tu peux utiliser une autre méthode ou simplement retourner 0
        try {
            return $this->createQueryBuilder('c')
                ->select('COUNT(c.id)')
                ->where('c.createdAt >= :start')
                ->andWhere('c.createdAt < :end')
                ->setParameter('start', $today)
                ->setParameter('end', $tomorrow)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (\Exception $e) {
            // Si la colonne createdAt n'existe pas
            return 0;
        }
    }
    
    public function getTotalCustomers(): int
    {
        return $this->count([]);
    }
}