<?php

namespace App\Repository\Sales\Order\Shipping;

use App\Entity\Sales\Order\Shipping\History;
use App\Repository\AbstractRepository;

/**
 * Repository class of order shipping.
 * 
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class HistoryRepository extends AbstractRepository
{
    protected $entityClass = History::class;

    /**
     * Return shiping history.
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

        return $this->createPaginator($qb, $currentPage, $limit);
    }     
}
