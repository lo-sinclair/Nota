<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


	/**
	 * @return array<Product>
	 */
	public function findNewest(): array {
		return $this
			->createQueryBuilder('p')
			->setMaxResults(4)
			->orderBy('p.createAt', 'DESC')
			->getQuery()
			->getResult();
	}

	/**
	 * @return array<Product>
	 */
	public function findPromo(): array {
		return $this
			->createQueryBuilder('p')
			->where('p.promo = 1')
			->setMaxResults(4)
			->orderBy('p.updateAt', 'DESC')
			->getQuery()
			->getResult();
	}

	/**
	 * @return array<Product>
	 */
	public function findByCategory(Category $category): array {
		return $this
			->createQueryBuilder('p')
			->join('p.category', 'c')
			->where('c = :category')
			->setParameter('category', $category)
			->getQuery()
			->getResult();
	}

}
