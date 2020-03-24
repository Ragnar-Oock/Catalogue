<?php

namespace App\Repository;

use App\Entity\Collec;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Collec|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collec|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collec[]    findAll()
 * @method Collec[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollecRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collec::class);
    }

    // /**
    //  * @return Collec[] Returns an array of Collec objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Collec
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
