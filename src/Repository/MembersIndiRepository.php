<?php

namespace App\Repository;

use App\Entity\MembersIndi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembersIndi>
 *
 * @method MembersIndi|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembersIndi|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembersIndi[]    findAll()
 * @method MembersIndi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembersIndiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembersIndi::class);
    }

    public function add(MembersIndi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MembersIndi $entity, bool $flush = false): void
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

	public function findAllIndirects($asso, $year) {
		return $this->createQueryBuilder('m')
			->select('COUNT(m.id) AS indirect_count')
			->where('m.membership_date LIKE :year')
			->andWhere('m.members_of = :assoId')
			->setParameters([
				'year' => '%' . $year . '%',
				'assoId' => $asso,
			])
           	->getQuery()
           	->getSingleScalarResult();
	}

//    /**
//     * @return MembersIndi[] Returns an array of MembersIndi objects
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

//    public function findOneBySomeField($value): ?MembersIndi
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
