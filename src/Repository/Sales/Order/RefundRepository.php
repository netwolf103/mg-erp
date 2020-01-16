<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Refund;

use App\Repository\AbstractRepository;

/**
 * Repository class of refund order.
 * 
 * @method Refund|null find($id, $lockMode = null, $lockVersion = null)
 * @method Refund|null findOneBy(array $criteria, array $orderBy = null)
 * @method Refund[]    findAll()
 * @method Refund[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RefundRepository extends AbstractRepository
{
    protected $entityClass = Refund::class;

    /**
     * Return refund orders.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
        ;

        if (isset($query['created_at'])) {
            $from = ($query['created_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['created_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);

            $qb->andWhere('r.created_at >= :from')
                ->andWhere('r.created_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }

        if (isset($query['refunded_at'])) {
            $from = ($query['refunded_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['refunded_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);
            
            $qb->andWhere('r.refunded_at >= :from')
                ->andWhere('r.refunded_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }        

        if (isset($query['sku'])) {
            $qb->andWhere('r.sku = :sku')
                ->setParameter('sku', $query['sku']);
        }

        if (isset($query['status'])) {
            $qb->andWhere('r.status = :status')
                ->setParameter('status', $query['status']);
        }                  

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
