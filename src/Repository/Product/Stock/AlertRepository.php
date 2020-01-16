<?php

namespace App\Repository\Product\Stock;

use App\Entity\Product\Stock\Alert;
use App\Repository\AbstractRepository;

/**
 * Repository class of product stock alert.
 * 
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AlertRepository extends AbstractRepository
{
    protected $entityClass = Alert::class;

    /**
     * Return product alerts.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = self::DEFAULT_PAGER_LIMIT)
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
        ;

        if (isset($query['sku'])) {
            $qb->innerJoin('a.product', 'p')
                ->andWhere('p.sku LIKE :sku')
                ->setParameter('sku', '%' . $query['sku'] . '%');
        }        

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
