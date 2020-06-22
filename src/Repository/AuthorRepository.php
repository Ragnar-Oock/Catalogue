<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findAllInOrder($asc = true)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.name', $asc ? 'ASC' : 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }

    public function pickAtRandom($limit=5)
    {
        $em = $this->getEntityManager();
        // get max disponible id value
        $max = $em->createQuery('SELECT MAX(a.id) FROM App\Entity\Author a')->getSingleScalarResult();

        // select authors above a random index generated between 1 and the max id value
        return $this->createQueryBuilder('a')
        ->andWhere('a.id >= :rand')
        ->setParameter('rand', rand(1, $max-$limit))
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Author[] Returns an array of Author objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
