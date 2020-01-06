<?php

namespace App\Repository\Sales;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Address;

use App\Repository\AbstractRepository;

/**
 * Sale order repository.
 *
 * @method SaleOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleOrder[]    findAll()
 * @method SaleOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderRepository extends AbstractRepository
{
    protected $entityClass = SaleOrder::class;

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
        $qb = $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
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

        if (isset($query['increment_id'])) {
            $qb->andWhere('o.increment_id LIKE :increment_id')
                ->setParameter('increment_id', '%' . $query['increment_id'] . '%');
        }

        if (isset($query['status'])) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $query['status']);
        }

        if (isset($query['tracking_number_to_platform_synced'])) {
            $tracking_number_to_platform_synced = (bool) $query['tracking_number_to_platform_synced'];

            if ($tracking_number_to_platform_synced) {
                $qb->andWhere('o.tracking_number_to_platform_synced = :tracking_number_to_platform_synced')
                    ->setParameter('tracking_number_to_platform_synced', 1);
            } else {
                $qb->andWhere('o.tracking_number_to_platform_synced = :tracking_number_to_platform_synced OR o.tracking_number_to_platform_synced IS NULL')
                    ->setParameter('tracking_number_to_platform_synced', 0);                
            }
        }        

        if (isset($query['email'])) {
            $qb->andWhere('o.customer_email LIKE :email')
                ->setParameter('email', '%' . $query['email'] . '%');
        } 

        if (isset($query['order_type'])) {
            $qb->andWhere('o.orderType = :order_type')
                ->setParameter('order_type', $query['order_type']);
        }  

        if (isset($query['shipping_method'])) {
            $qb->andWhere('o.shipping_method = :shipping_method')
                ->setParameter('shipping_method', $query['shipping_method']);
        }                                

        if (isset($query['sku'])) {
            $qb->innerJoin('o.items', 'i')
                ->andWhere('i.sku = :sku')
                ->setParameter('sku', $query['sku']);
        }

        if (isset($query['payment_method'])) {
            $qb->innerJoin('o.payment', 'p')
                ->andWhere('p.method = :payment_method')
                ->setParameter('payment_method', $query['payment_method']);
        }

        if (isset($query['expedited'])) {
            $qb->leftJoin('o.expedited', 'expedited');

            $expedited = (bool) $query['expedited'];
            if ($expedited) {
                $qb->andWhere('expedited.id IS NOT NULL');
            } else {
                $qb->andWhere('expedited.id IS NULL');
            }
        }        

        if (isset($query['bill'])) {
            $qb->innerJoin('o.address', 'bill')
                ->andWhere('bill.address_type = :address_type')
                ->setParameter('address_type', Address::ADDRESS_BILLING)
                ->andWhere('bill.firstname LIKE  :firstname')
                ->setParameter('firstname', '%' .$query['bill']. '%')
                ->orWhere('bill.lastname LIKE  :lastname')
                ->setParameter('lastname', '%' .$query['bill']. '%');                                
        } 

        if (isset($query['ship'])) {
            $qb->innerJoin('o.address', 'ship')
                ->andWhere('ship.address_type = :address_type')
                ->setParameter('address_type', Address::ADDRESS_SHIPPING)
                ->andWhere('ship.firstname LIKE  :firstname')
                ->setParameter('firstname', '%' .$query['ship']. '%')
                ->orWhere('ship.lastname LIKE  :lastname')
                ->setParameter('lastname', '%' .$query['ship']. '%');                                
        }

        if (isset($query['addressWrong'])) {
            $alias = $qb->getAllAliases();

            if (!in_array('ship', $alias)) {
                $qb->innerJoin('o.address', 'ship');
            }

            if ($query['addressWrong'] == 1) {
                $qb->andWhere('ship.isWrong = 1');
            } else {
                 $qb->andWhere('ship.isWrong = 0 OR ship.isWrong IS Null');
            }
            $qb->andWhere('ship.address_type = :address_type')
                ->setParameter('address_type', Address::ADDRESS_SHIPPING);                                
        }                                               

        return $this->createPaginator($qb, $currentPage, $limit);
    }

    public function findPlatformNoSyncedTrackingNumber()
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.tracking_number_to_platform_synced is NULL OR o.tracking_number_to_platform_synced = 0')
            ->leftJoin('o.shipments', 's')
            ->andWhere('s.id is NOT NULL')
            ->leftJoin('o.payment_transactions', 'p')
            ->andWhere('p.id is NOT NULL')            
            ->getQuery();

        return $qb->execute();        
    }  

    public function findTransactionIsNull()
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.payment_transactions', 't')
            ->where('t.id is NULL')
            ->getQuery();

        return $qb->execute();          
    }

    public function findByMethod(string $method_name)
    {
        $qb = $this->createQueryBuilder('o')
            ->innerJoin('o.payment', 'p')
            ->where('p.method = :payment_method')
            ->setParameter('payment_method', $method_name)
            ->getQuery();

        return $qb->execute();          
    }

    public function countAll()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id) as total')
            ->getQuery()
            ->getSingleScalarResult();        
    }

    public function countNotShipments()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id) as total')
            ->leftJoin('o.shipments', 's')
            ->where('s.id is NULL')
            ->getQuery()
            ->getSingleScalarResult();              
    }    

    public function countToday()
    {
        $datetime = new \DateTimeImmutable();
      
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id) as total')
            ->where('o.created_at >= :start')
            ->andWhere('o.created_at <= :end')
            ->setParameter('start', $datetime->format('Y-m-d 00:00:00'))
            ->setParameter('end', $datetime->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countWeek()
    {
        if (date('w') == 1) {
            $timestamp = time();
        } else {
            $timestamp = strtotime('previous monday');
        }

        $start = new \DateTimeImmutable(date('Y-m-d h:i:s', $timestamp));
        $end = new \DateTimeImmutable();

        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id) as total')
            ->where('o.created_at >= :start')
            ->andWhere('o.created_at <= :end')
            ->setParameter('start', $start->format('Y-m-d 00:00:00'))
            ->setParameter('end', $end->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countMonth()
    {
        $datetime = new \DateTimeImmutable();

        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id) as total')
            ->where('o.created_at >= :start')
            ->andWhere('o.created_at <= :end')
            ->setParameter('start', $datetime->format('Y-m-01 00:00:00'))
            ->setParameter('end', $datetime->format('Y-m-d h:i:s'))
            ->getQuery()
            ->getSingleScalarResult();        
    }
}
