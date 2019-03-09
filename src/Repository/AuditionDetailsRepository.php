<?php

namespace App\Repository;

use App\Entity\AuditionDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuditionDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuditionDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuditionDetails[]    findAll()
 * @method AuditionDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditionDetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuditionDetails::class);
    }

//    /**
//     * @return AuditionDetails[] Returns an array of AuditionDetails objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AuditionDetails
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
