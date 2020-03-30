<?php

namespace App\Repository;

use App\Entity\Edition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Edition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Edition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Edition[]    findAll()
 * @method Edition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edition::class);
    }

    public function searchEdition(String $search)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('ed.name LIKE :search')
            ->andWhere('d.title LIKE :search')
            ->andWhere('a.name LIKE :search')
            ->leftJoin('e.editor', 'ed')
            ->leftJoin('e.document', 'd')
            ->leftJoin('e.author', 'a')
            ->leftJoin('e.type', 't')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAll()
    {
        return $this->createQueryBuilder('e')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Edition[] Returns an array of Edition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Edition
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
