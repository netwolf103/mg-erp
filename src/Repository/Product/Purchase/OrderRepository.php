<?php

namespace App\Repository\Product\Purchase;

use App\Entity\Product\Purchase\Order;
use App\Repository\AbstractRepository;

/**
 * Repository class of product purchase order.
 * 
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderRepository extends AbstractRepository
{
    protected $entityClass = Order::class;

    /**
     * Return purchase orders.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC');
        ;

        if (isset($query['created_at'])) {
            $from = ($query['created_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['created_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);

            $qb->andWhere('o.created_at >= :from')
                ->andWhere('o.created_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }      

        if (isset($query['updated_at'])) {
            $from = ($query['updated_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['updated_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);            

            $qb->andWhere('o.updated_at >= :from')
                ->andWhere('o.updated_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }  

        if (isset($query['sku'])) {
            $qb->innerJoin('o.parent', 'p')
            	->andWhere('p.sku = :sku')
                ->setParameter('sku', $query['sku'])
            ;
        }

        if (isset($query['status'])) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $query['status'])
            ;
        }

        if (isset($query['track_number'])) {
            $qb->andWhere('o.track_number = :track_number')
                ->setParameter('track_number', $query['track_number'])
            ;
        }  

        if (isset($query['order_number'])) {
            $qb->andWhere('o.source_order_number = :order_number')
                ->setParameter('order_number', $query['order_number'])
            ;
        }                                      

        return $this->createPaginator($qb, $currentPage, $limit);
    }        
}
