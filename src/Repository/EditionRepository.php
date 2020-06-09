<?php

namespace App\Repository;

use App\Entity\Edition;
use App\Entity\Reservation;
use DateTimeInterface;
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
            ->addSelect('a', 'p')
            ->addSelect('w')
            ->addSelect('t')
            ->addSelect('ed')
            ->orWhere('ed.name LIKE :search')
            ->orWhere('d.title LIKE :search')
            ->orWhere('a.name LIKE :search')
            ->leftJoin('e.editor', 'ed')
            ->leftJoin('e.document', 'd')
            ->leftJoin('e.writers', 'w')
            ->leftJoin('w.author', 'a')
            ->leftJoin('w.participationType', 'p')
            ->leftJoin('e.type', 't')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return Edition[] Returns an array of Edition objects
     */
    public function findByAuthor($authorId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.authors = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
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

    public function isAvailable(Edition $edition, DateTimeInterface $begining, DateTimeInterface $ending, Reservation $reservation = null)
    {
        // if the begining of the any reservation is before the end of the reuested periode and the end of the reservation after the requested time periode, there is an overlap, the edition is not available
        // exclude the passed reservation for check (for edit)
        $query = $this->createQueryBuilder('e')
            ->innerJoin('e.reservations', 'r')
            ->andWhere('e = :edition')
            ->andWhere('r.beginingAt < :ending AND :begining < r.endingAt')
            ->setParameter('edition', $edition)
            ->setParameter('begining', $begining)
            ->setParameter('ending', $ending)
            ->setMaxResults(1)
        ;
        if ($reservation != null) {
            $query->andWhere('r != :reservation')
                ->setParameter('reservation', $reservation);
        }

        // if ther is no result the edition is available on the given time range
        return count($query->getQuery()->getResult()) === 0;
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
