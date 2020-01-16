<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Invoice;

use App\Repository\AbstractRepository;

/**
 * Repository class of order invoice.
 * 
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class InvoiceRepository extends AbstractRepository
{
    protected $entityClass = Invoice::class;

    /**
     * Return order invoices.
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

        if (isset($query['increment_id'])) {
            $qb->andWhere('i.increment_id LIKE :increment_id')
                ->setParameter('increment_id', '%' . $query['increment_id'] . '%');
        }

        if (isset($query['order_increment_id'])) {
            $qb->innerJoin('i.parent', 'o')
                ->andWhere('o.increment_id LIKE :order_increment_id')
                ->setParameter('order_increment_id', '%' . $query['order_increment_id'] . '%');
        }        

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
