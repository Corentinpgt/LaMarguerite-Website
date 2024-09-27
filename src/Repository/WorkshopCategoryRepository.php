<?php

namespace App\Repository;

use App\Entity\WorkshopCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkshopCategory>
 *
 * @method WorkshopCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkshopCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkshopCategory[]    findAll()
 * @method WorkshopCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkshopCategory::class);
    }

//    /**
//     * @return WorkshopCategory[] Returns an array of WorkshopCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkshopCategory
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
