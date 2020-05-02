<?php

namespace App\Repository;

use App\Entity\ParticipationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ParticipationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipationType[]    findAll()
 * @method ParticipationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipationType::class);
    }

    // /**
    //  * @return ParticipationType[] Returns an array of ParticipationType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParticipationType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
