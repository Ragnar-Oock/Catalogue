<?php

namespace App\Repository;

use App\Entity\Editor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Editor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Editor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Editor[]    findAll()
 * @method Editor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Editor::class);
    }

    public function findInstance(Editor $editor)
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.name = :name')
            ->setParameter('name', $editor->getName());

            if (!empty($editor->getAddress())) {
                
                $query->andWhere('e.address = :address')
                    ->setParameter('address', $editor->getAddress());
            }

            return $query->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Editor[] Returns an array of Editor objects
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
    public function findOneBySomeField($value): ?Editor
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
