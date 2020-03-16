<?php

namespace App\Repository;

use App\Entity\Fond;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Fond|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fond|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fond[]    findAll()
 * @method Fond[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FondRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fond::class);
    }

    // /**
    //  * @return Fond[] Returns an array of Fond objects
    //  */
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
    public function findOneBySomeField($value): ?Fond
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
