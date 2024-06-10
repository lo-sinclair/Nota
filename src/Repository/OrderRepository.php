<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

	public function sumOfOrder($orderId): int {
		$dql = "SELECT SUM(p.price) 
			FROM App\Entity\Order o
			JOIN o.products p
			WHERE o.id = :id
			GROUP BY o.id 
			";

		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('id', $orderId);
		return $query->getSingleResult()[1];
	}

	public function findUsersCompleteOrders($user): array {
		$query =$this->createQueryBuilder('o')
			->select('o, SUM(p.price) AS sum')
			->where('o.user = :user')
			->join('o.products', 'p')
			->andWhere('o.status = :status')
			->setParameter('user', $user)
			->setParameter('status', Status::COMPLETED)
			->orderBy('o.updateAt')
			->groupBy('o.id')
			->setMaxResults(6)
			->getQuery()
			->getResult()
		;
		return $query;
	}

	public function findUsersActiveOrders($user): array {
		return$this->createQueryBuilder('o')
		           ->select('o, SUM(p.price) AS sum')
		           ->where('o.user = :user')
		           ->join('o.products', 'p')
		           ->andWhere('o.status NOT IN (:status1, :status2)')
		           ->setParameter('user', $user)
		           ->setParameter('status1', Status::COMPLETED)
		           ->setParameter('status2', Status::CANCELED)
		           ->orderBy('o.updateAt')
		           ->groupBy('o.id')
		           ->setMaxResults(6)
		           ->getQuery()
		           ->getResult()
			;
	}

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public_html function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public_html function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
