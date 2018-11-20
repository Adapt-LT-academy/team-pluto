<?php

namespace App\Repository;

use App\Entity\Ferries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ferries|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ferries|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ferries[]    findAll()
 * @method Ferries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FerriesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ferries::class);
    }

//    /**
//     * @return Ferries[] Returns an array of Ferries objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ferries
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
