<?php

namespace App\Repository;

use App\Entity\PatientSophro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PatientSophro>
 *
 * @method PatientSophro|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientSophro|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientSophro[]    findAll()
 * @method PatientSophro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientSophroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientSophro::class);
    }

    public function add(PatientSophro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PatientSophro $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function findOneByIdPatient(int $idPatient): ?PatientSophro
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.idPatient = :idPatient')
			->setParameter('idPatient', $idPatient)
			->getQuery()
			->getOneOrNullResult()
		;
	}

//    /**
//     * @return PatientSophro[] Returns an array of PatientSophro objects
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

//    public function findOneBySomeField($value): ?PatientSophro
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
