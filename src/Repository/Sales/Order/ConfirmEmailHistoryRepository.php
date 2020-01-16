<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\ConfirmEmailHistory;
use App\Repository\AbstractRepository;

/**
 * Repository class of order confirm email history.
 * 
 * @method ConfirmEmailHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfirmEmailHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfirmEmailHistory[]    findAll()
 * @method ConfirmEmailHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ConfirmEmailHistoryRepository extends AbstractRepository
{
    protected $entityClass = ConfirmEmailHistory::class;

    /**
     * Return order onfirm emails.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('h')
            ->orderBy('h.id', 'DESC')
        ;

        if (isset($query['parent'])) {
            $qb->andWhere('h.parent = :parent')
                ->setParameter('parent', $query['parent']);
        }                                         

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
