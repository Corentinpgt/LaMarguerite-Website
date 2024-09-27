<?php

namespace App\Repository;

use App\Entity\MembershipAsso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembershipAsso>
 *
 * @method MembershipAsso|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembershipAsso|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembershipAsso[]    findAll()
 * @method MembershipAsso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipAssoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembershipAsso::class);
    }

//    /**
//     * @return MembershipAsso[] Returns an array of MembershipAsso objects
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

//    public function findOneBySomeField($value): ?MembershipAsso
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
