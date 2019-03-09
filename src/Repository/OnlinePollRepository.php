<?php

namespace App\Repository;

use App\Entity\OnlinePoll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OnlinePoll|null find($id, $lockMode = null, $lockVersion = null)
 * @method OnlinePoll|null findOneBy(array $criteria, array $orderBy = null)
 * @method OnlinePoll[]    findAll()
 * @method OnlinePoll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OnlinePollRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OnlinePoll::class);
    }

    // /**
    //  * @return OnlinePoll[] Returns an array of OnlinePoll objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OnlinePoll
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
