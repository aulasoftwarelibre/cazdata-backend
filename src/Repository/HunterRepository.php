<?php

namespace App\Repository;

use App\Entity\Hunter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hunter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hunter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hunter[]    findAll()
 * @method Hunter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HunterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hunter::class);
    }

    // /**
    //  * @return Hunter[] Returns an array of Hunter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hunter
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
