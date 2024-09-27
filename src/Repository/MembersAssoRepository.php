<?php

namespace App\Repository;

use App\Entity\MembersAsso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembersAsso>
 *
 * @method MembersAsso|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembersAsso|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembersAsso[]    findAll()
 * @method MembersAsso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembersAssoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembersAsso::class);
    }

    public function add(MembersAsso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MembersAsso $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function countByYear($year)
	{
		return $this->createQueryBuilder('m')
			->select('COUNT(m.id) AS year_count')
			->where('m.membership_date LIKE :year')
			->setParameter('year', '%' . $year . '%')
			->getQuery()
			->getSingleScalarResult();
	}
    
    public function sumPaymentByYear($year)
    {
        return $this->createQueryBuilder('m')
            ->select('SUM(m.payment) AS total_payments')
            ->where('m.membership_date LIKE :year')
            ->setParameter('year', '%' . $year . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return MembersAsso[] Returns an array of MembersAsso objects
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

//    public function findOneBySomeField($value): ?MembersAsso
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
