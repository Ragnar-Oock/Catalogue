<?php

namespace App\Repository;

use App\Entity\Edition;
use App\Entity\Reservation;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

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

    public function findCanceled($canceled=true, $limit=0)
    {
        $query = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC')
            ->andWhere('r.canceled = :canceled')
            ->setParameter('canceled', $canceled)
            ;
        // limit results
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()->getResult();
    }

    public function findPendingValidation($limit=0)
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.validatedAt IS NULL')
            ->orderBy('r.id', 'ASC')
            ->andWhere('r.canceled = FALSE');
        // limit results
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($user, $limit=0)
    {
        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.edition', 'e')
            ->innerJoin('e.document', 'd')
            ->andWhere('r.user=:user')
            ->orderBy('r.submitedAt', 'DESC')
            ->setParameter('user', $user)
        ;

        // limit results
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }

    public function findByTimeRange(Edition $edition, DateTimeInterface $begining, DateTimeInterface $ending, $limit=0)
    {
        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.edition', 'e')
            ->innerJoin('e.document', 'd')
            ->andWhere('e = :edition')
            ->andWhere('r.beginingAt < :ending AND :begining < r.endingAt')
            ->setParameter('edition', $edition)
            ->setParameter('begining', $begining)
            ->setParameter('ending', $ending);
        // limit results
        if ($limit != 0) {
            $query->setMaxResults($limit);
        }
        return $query->getQuery()->getResult();
    }


    public function search(DateTimeInterface $submitedAtBegining, DateTimeInterface $submitedAtEnd, DateTimeInterface $rangeBegining, DateTimeInterface $rangeEnd, Boolean $canceled, Boolean $validated, Boolean $haveCommentaire, $user)
    {
        $query = $this->createQueryBuilder('r');

        if ($submitedAtBegining != null) {
            $query->andWhere('r.submitedAt > :submitedAtBegining')
                ->setParameter('submitedAtBegining', $submitedAtBegining);
        }
        if ($submitedAtEnd != null) {
            $query->andWhere('r.submitedAt < :submitedAtEnd')
                ->setParameter('submitedAtEnd', $submitedAtEnd);
        }
        if ($rangeBegining != null) {
            $query->andWhere('r.beginingAt > :rangeBegining')
                ->setParameter('rangeBegining', $rangeBegining);
        }
        if ($rangeEnd != null) {
            $query->andWhere('r.endingAt < :rangeEnd')
                ->setParameter('rangeEnd', $rangeEnd);
        }

        if ($canceled !== null) {
            $query->andWhere('r.canceled = :canceled')
                ->setParameter('canceled', $canceled);
        }
        if ($validated !== null) {
            $query->andWhere('r.validated = :validated')
                ->setParameter('validated', $validated);
        }
        if ($haveCommentaire !== null) {
            $query->andWhere('r.commentaire !== NULL');
        }

        if ($user != null) {
            $query->innerJoin('user', 'u')
                ->andWhere('user.email LIKE :user OR user.firstname LIKE :user OR user.lastname LIKE :user')
                ->setParameter('user', "%$user%");
        }

        return $query->getQuery()->getResult();
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
