<?php

namespace App\Repository;

use App\Entity\Ferry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ferry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ferry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ferry[]    findAll()
 * @method Ferry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FerryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ferry::class);
    }

//    /**
//     * @return Ferry[] Returns an array of Ferry objects
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
    public function findOneBySomeField($value): ?Ferry
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
