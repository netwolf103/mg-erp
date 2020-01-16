<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Shipment;

use App\Repository\AbstractRepository;

/**
 * Repository class of order shipment.
 * 
 * @method Shipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shipment[]    findAll()
 * @method Shipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentRepository extends AbstractRepository
{
	protected $entityClass = Shipment::class;

    /**
     * Return shipments.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
        ;

        if (isset($query['created_at'])) {
            $from = ($query['created_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['created_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);

            $qb->andWhere('s.created_at >= :from')
                ->andWhere('s.created_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);            
        }

        if (isset($query['increment_id'])) {
            $qb->andWhere('s.increment_id LIKE :increment_id')
                ->setParameter('increment_id', '%' . $query['increment_id'] . '%');
        }

        if (isset($query['track_number'])) {
            $qb->innerJoin('s.tracks', 't')
                ->andWhere('t.track_number = :track_number')
                ->setParameter('track_number', $query['track_number']);
        }        

        if (isset($query['order_increment_id'])) {
            $qb->innerJoin('s.parent', 'o')
                ->andWhere('o.increment_id LIKE :order_increment_id')
                ->setParameter('order_increment_id', '%' . $query['order_increment_id'] . '%');
        }        

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}