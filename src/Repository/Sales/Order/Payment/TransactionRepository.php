<?php

namespace App\Repository\Sales\Order\Payment;

use App\Entity\Sales\Order\Payment\Transaction;
use App\Repository\AbstractRepository;

/**
 * Repository class of order payment transaction.
 * 
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TransactionRepository extends AbstractRepository
{
    protected $entityClass = Transaction::class;

    /**
     * Return transaction.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
        ;      

        if (isset($query['txn_id'])) {
            $qb->andWhere('t.txn_id = :txn_id')
                ->setParameter('txn_id', $query['txn_id']);
        }

        if (isset($query['parent_txn_id'])) {
            $qb->andWhere('t.parent_txn_id = :parent_txn_id')
                ->setParameter('parent_txn_id', $query['parent_txn_id']);
        }        

        if (isset($query['txn_type'])) {
            $qb->andWhere('t.txn_type = :txn_type')
                ->setParameter('txn_type', $query['txn_type']);
        }        

        if (isset($query['increment_id'])) {
            $qb->innerJoin('t.parent_order', 'o')
                ->andWhere('o.increment_id = :increment_id')
                ->setParameter('increment_id', $query['increment_id']);
        }                                

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
