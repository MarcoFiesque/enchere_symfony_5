<?php

namespace App\Repository;

use App\Entity\U;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method U|null find($id, $lockMode = null, $lockVersion = null)
 * @method U|null findOneBy(array $criteria, array $orderBy = null)
 * @method U[]    findAll()
 * @method U[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class URepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, U::class);
    }

    // /**
    //  * @return U[] Returns an array of U objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?U
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
