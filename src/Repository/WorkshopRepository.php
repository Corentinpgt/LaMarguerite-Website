<?php

namespace App\Repository;

use App\Entity\Workshop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workshop>
 *
 * @method Workshop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workshop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workshop[]    findAll()
 * @method Workshop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkshopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workshop::class);
    }

    public function add(Workshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Workshop $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


	public function findAllByContent($value): array
	{

		$qb = $this->createQueryBuilder('w');

		$qb->innerJoin('w.contributor', 'c');
	
		return $qb
			->where($qb->expr()->orX(
				$qb->expr()->like('w.name', ':val'),
				$qb->expr()->like('w.info_workshop', ':val'),
				$qb->expr()->like('w.activity_workshop', ':val'),
				$qb->expr()->like('w.place', ':val'),
				$qb->expr()->like('w.day', ':val'),
				$qb->expr()->like('w.hours', ':val'),
				$qb->expr()->like('c.firstname', ':val'),
				$qb->expr()->like('c.lastname', ':val'),
			))
			->setParameter('val', '%'.$value.'%')
			->getQuery()
			->getResult();

	}

//    /**
//     * @return Workshop[] Returns an array of Workshop objects
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

   
}
