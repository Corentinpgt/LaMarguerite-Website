<?php

namespace App\Repository;

use App\Entity\MembershipIndividual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembershipIndividual>
 *
 * @method MembershipIndividual|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembershipIndividual|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembershipIndividual[]    findAll()
 * @method MembershipIndividual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipIndividualRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembershipIndividual::class);
    }


//    /**
//     * @return MembershipIndividual[] Returns an array of MembershipIndividual objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MembershipIndividual
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
