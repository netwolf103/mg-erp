<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Expedited;

use App\Repository\AbstractRepository;

/**
 * Repository for Order Expedited.
 * 
 * @method Expedited|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expedited|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expedited[]    findAll()
 * @method Expedited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ExpeditedRepository extends AbstractRepository
{
    protected $entityClass = Expedited::class;

    /**
     * Return orders.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
        ;

        $qb->innerJoin('e.parent', 'o')
            ->andWhere('o.status != :order_status')
            ->setParameter('order_status', \App\Entity\SaleOrder::ORDER_STATUS_COMPLETE);        

        if (isset($query['increment_id'])) {
            $qb->andWhere('o.increment_id = :increment_id')
                ->setParameter('increment_id', $query['increment_id']);
        }

        if (isset($query['status'])) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $query['status']);
        }                

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
