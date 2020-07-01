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
            ->addSelect('a', 'p', 'w', 't', 'd', 'ed')
            ->orWhere('ed.name LIKE :searchlike')
            ->orWhere('d.title LIKE :searchlike')
            ->orWhere('a.name LIKE :searchlike')

            ->orWhere('MATCH_AGAINST(ed.name) AGAINST(:search boolean)>0')
            ->orWhere('MATCH_AGAINST(d.title, d.subtitle, d.alttitle) AGAINST(:search boolean)>0')
            ->orWhere('MATCH_AGAINST(a.name) AGAINST(:search boolean)>0')
            ->leftJoin('e.editor', 'ed')
            ->leftJoin('e.document', 'd')
            ->leftJoin('e.writers', 'w')
            ->leftJoin('w.author', 'a')
            ->leftJoin('w.participationType', 'p')
            ->leftJoin('e.type', 't')
            ->setParameter('searchlike', '%'.$search.'%')
            ->setParameter('search', $search)
            ->getQuery()
            ->getResult()
        ;
    }

    public function advencedSearchEdition(Array $values)
    {
        $v = [
            'publisheAfter' => empty($values['publisheAfter']),
            'publishedBefore' => empty($values['publishedBefore']),
            'type' => empty($values['type']),
            'title' => empty($values['title']),
            'author' => empty($values['author']),
            'editor' => empty($values['editor']),
            'issn' => empty($values['issn']),
            'isbn' => empty($values['isbn']),
        ];
        $query = $this->createQueryBuilder('e')
            ->addSelect('a', 'p', 'w', 't', 'd', 'ed');
        
            if (!empty($values['publisheAfter'])) {
                $query->andWhere('e.publishedAt > :publisheAfter')
                    ->setParameter('publisheAfter', $values['publisheAfter']);
            }
            if (!empty($values['publishedBefore'])) {
                $query->andWhere('e.publishedAt < :publishedBefore')
                    ->setParameter('publishedBefore', $values['publishedBefore']);
            }
            if (!empty($values['type'])) {
                $query->andWhere('e.type = :type')
                    ->setParameter('type', $values['type']);
            }
            if (!empty($values['title'])) {
                $query->andWhere('d.title LIKE :title OR d.subtitle LIKE :title OR d.alttitle LIKE :title')
                    ->setParameter('title', '%'.$values['title'].'%');
            }
            if (!empty($values['author'])) {
                $query->andWhere('a.name LIKE :author')
                    ->setParameter('author', '%'.$values['author'].'%');
            }
            if (!empty($values['editor'])) {
                $query->andWhere('ed.name LIKE :editor')
                    ->setParameter('editor', '%'.$values['editor'].'%');
            }
            if (!empty($values['issn'])) {
                $query->andWhere('e.issn LIKE :issn')
                    ->setParameter('issn', $values['issn']);
            }
            if (!empty($values['isbn'])) {
                $query->andWhere('e.isbn LIKE :isbn')
                    ->setParameter('isbn', $values['isbn']);
            }
            if (!empty($values['search'])) {
                $query->andWhere('ed.name LIKE :searchlike 
                OR d.title LIKE :searchlike 
                OR a.name LIKE :searchlike 
                OR MATCH_AGAINST(ed.name) AGAINST(:search boolean)>0 
                OR MATCH_AGAINST(d.title, d.subtitle, d.alttitle) AGAINST(:search boolean)>0 
                OR MATCH_AGAINST(a.name) AGAINST(:search boolean)>0')
                ->setParameter('searchlike', '%'.$values['search'].'%')
                ->setParameter('search', $values['search']);
            }
            
            $query->leftJoin('e.editor', 'ed')
            ->leftJoin('e.document', 'd')
            ->leftJoin('e.writers', 'w')
            ->leftJoin('w.author', 'a')
            ->leftJoin('w.participationType', 'p')
            ->leftJoin('e.type', 't')
            ->getQuery()
            ->getResult();
        ;

        return $query;
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

    public function getNewInventoryNumber()
    {
        return $this->createQueryBuilder('e')
            ->select('MAX(e.inventoryNumber)')
            ->getQuery()
            ->getSingleResult()[1] + 1
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
