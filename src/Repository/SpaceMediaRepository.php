<?php

namespace App\Repository;

use App\Entity\SpaceMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SpaceMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceMedia[]    findAll()
 * @method SpaceMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceMedia::class);
    }

    // /**
    //  * @return SpaceMedia[] Returns an array of SpaceMedia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpaceMedia
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
