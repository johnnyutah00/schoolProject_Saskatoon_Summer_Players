<?php

namespace App\Repository;

use App\Entity\SuggestedShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SuggestedShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuggestedShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuggestedShow[]    findAll()
 * @method SuggestedShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuggestedShowRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SuggestedShow::class);
    }

    // /**
    //  * @return SuggestedShow[] Returns an array of SuggestedShow objects
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
    public function findOneBySomeField($value): ?SuggestedShow
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
