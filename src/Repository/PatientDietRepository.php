<?php

namespace App\Repository;

use App\Entity\PatientDiet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PatientDiet>
 *
 * @method PatientDiet|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientDiet|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientDiet[]    findAll()
 * @method PatientDiet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientDietRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientDiet::class);
    }

    public function add(PatientDiet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PatientDiet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function findOneByIdPatient(int $idPatient): ?PatientDiet
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.idPatient = :idPatient')
			->setParameter('idPatient', $idPatient)
			->getQuery()
			->getOneOrNullResult()
		;
	}

	// /**
	//  * @return PatientDiet[] Returns an array of PatientDiet objects
	//  */
	/*
	public function findByExampleField($value): array
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	// /**
	//  * @return PatientDiet[] Returns an array of PatientDiet objects
	//  */
	/*
	public function findByExampleField($value): array
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	// /**
	//  * @return PatientDiet[] Returns an array of PatientDiet objects
	//  */
	/*
	public function findByExampleField($value): array
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	// /**
	//  * @return PatientDiet[] Returns an array of PatientDiet objects
	//  */
	/*
	public function findByExampleField($value): array
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	// /**
	//  * @return PatientDiet[] Returns an array of PatientDiet objects
	//  */
	/*
	public function findBy

//    /**
//     * @return PatientDiet[] Returns an array of PatientDiet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PatientDiet
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
