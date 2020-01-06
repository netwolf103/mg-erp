<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Item;

use App\Repository\AbstractRepository;

/**
 * Sales order item repository.
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemRepository extends AbstractRepository
{
	protected $entityClass = Item::class;

    /**
     * Return order items.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('i')
            ->orderBy('i.id', 'DESC')
        ;

        if (isset($query['created_at'])) {
            $from = ($query['created_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['created_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);
            
            $qb->andWhere('i.created_at >= :from')
                ->andWhere('i.created_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }

        if (isset($query['sku'])) {
            $qb->andWhere('i.sku LIKE :sku')
                ->setParameter('sku', '%' . $query['sku'] . '%');
        }

        if (isset($query['name'])) {
            $qb->andWhere('i.name LIKE :name')
                ->setParameter('name', '%' . $query['name'] . '%');
        }                                           

        return $this->createPaginator($qb, $currentPage, $limit);
    }

	public function findBySku(string $sku)
	{
        $qb = $this->createQueryBuilder('i')
            ->where('i.sku = :sku')
            ->setParameter('sku', $sku)
            ->getQuery();

        return $qb->execute(); 		
	}
}
