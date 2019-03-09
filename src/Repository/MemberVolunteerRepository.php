<?php

namespace App\Repository;

use App\Entity\MemberVolunteer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MemberVolunteer|null find($id, $lockMode = null, $lockVersion = null)
 * @method MemberVolunteer|null findOneBy(array $criteria, array $orderBy = null)
 * @method MemberVolunteer[]    findAll()
 * @method MemberVolunteer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberVolunteerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MemberVolunteer::class);
    }

    // /**
    //  * @return MemberVolunteer[] Returns an array of MemberVolunteer objects
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
    public function findOneBySomeField($value): ?MemberVolunteer
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
