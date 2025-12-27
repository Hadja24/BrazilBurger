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

    public function findAllOrderedByName()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.account', 'a')
            ->orderBy('a.name', 'ASC')
            ->addOrderBy('a.surname', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function getTotalCustomers(): int
    {
        return $this->count([]);
    }

    // Ajouter cette méthode dans src/Repository/CustomerRepository.php
// Ajouter cette méthode dans src/Repository/CustomerRepository.php
public function findOneByFuzzySearch(?string $name = null, ?string $surname = null, ?string $phone = null): ?Customer
{
    $qb = $this->createQueryBuilder('c')
        ->join('c.account', 'a');

    $conditions = [];
    $parameters = [];

    if ($name) {
        $conditions[] = 'a.name LIKE :name';
        $parameters['name'] = '%' . $name . '%';
    }

    if ($surname) {
        $conditions[] = 'a.surname LIKE :surname';
        $parameters['surname'] = '%' . $surname . '%';
    }

    if ($phone) {
        $conditions[] = 'a.phone LIKE :phone';
        $parameters['phone'] = '%' . $phone . '%';
    }

    // Si aucun critère n'est fourni, retourner null
    if (empty($conditions)) {
        return null;
    }

    // Appliquer toutes les conditions avec AND
    $qb->where(implode(' AND ', $conditions));
    
    // Définir les paramètres
    foreach ($parameters as $key => $value) {
        $qb->setParameter($key, $value);
    }

    return $qb->getQuery()->getOneOrNullResult();
}
}