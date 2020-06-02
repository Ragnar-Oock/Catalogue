<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @return Reservation[] Returns Reservations based on its validation status
     */
    
    public function findByValidationStatus($validated, $limit=0)
    {
        $query = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC');
        // find validated
        if ($validated) {
            $query->andWhere('r.validated = TRUE');
        }
        // find rejected
        else {
            $query->andWhere('r.validated = FALSE')
                ->andWhere('r.validatedAt IS NOT NULL');
        }
        
        // limit results
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()->getResult();
    }


    public function findPendingValidation($limit=0)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.validatedAt IS NULL')
            ->orderBy('r.id', 'ASC')
            ->andWhere('r.canceled = TRUE')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($user, $limit=0)
    {
        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.edition', 'e')
            ->innerJoin('e.document', 'd')
            ->andWhere('r.user=:user')
            ->orderBy('r.validated', 'ASC')
            ->setParameter('user', $user)
        ;

        // limit results
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }

    public function getDisponibility(Reservation $reservation, DateTimeInterface $begining, DateTimeInterface $end)
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere()
        ;
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
