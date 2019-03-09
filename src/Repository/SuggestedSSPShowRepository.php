<?php

namespace App\Repository;

use App\Entity\SuggestedSSPShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SuggestedSSPShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuggestedSSPShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuggestedSSPShow[]    findAll()
 * @method SuggestedSSPShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuggestedSSPShowRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SuggestedSSPShow::class);
    }

    // /**
    //  * @return SuggestedSSPShow[] Returns an array of SuggestedSSPShow objects
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
    public function findOneBySomeField($value): ?SuggestedSSPShow
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
