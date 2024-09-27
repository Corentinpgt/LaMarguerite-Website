<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

	public function findAllByLatest()
	{
		return $this->createQueryBuilder('a')
			->where('a.isPublished = true')
			->orderBy('a.eventDate', 'DESC')
			->getQuery()
			->getResult();
	}


	public function findAllByContent($value): array
	{


		$qb = $this->createQueryBuilder('a');
		$qb->andWhere('a.title LIKE :val OR a.body LIKE :val OR a.eventDate LIKE :val');
		$qb->andWhere('a.eventDate IS NOT NULL');
		$qb->andWhere('a.isPublished = true');
		$qb->setParameter('val', '%'.$value.'%');
		$qb->orderBy('a.eventDate', 'DESC');
		return $qb->getQuery()->getResult();

	}
}
