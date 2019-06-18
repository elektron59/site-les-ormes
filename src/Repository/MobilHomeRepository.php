<?php

namespace App\Repository;

use App\Entity\MobilHome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MobilHome|null find($id, $lockMode = null, $lockVersion = null)
 * @method MobilHome|null findOneBy(array $criteria, array $orderBy = null)
 * @method MobilHome[]    findAll()
 * @method MobilHome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobilHomeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MobilHome::class);
    }

    // /**
    //  * @return MobilHome[] Returns an array of MobilHome objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MobilHome
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
