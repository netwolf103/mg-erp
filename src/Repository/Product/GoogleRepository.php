<?php

namespace App\Repository\Product;

use App\Entity\Product\Google;
use App\Repository\AbstractRepository;

/**
 * Repository for google product.
 * 
 * @method Google|null find($id, $lockMode = null, $lockVersion = null)
 * @method Google|null findOneBy(array $criteria, array $orderBy = null)
 * @method Google[]    findAll()
 * @method Google[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GoogleRepository extends AbstractRepository
{
    protected $entityClass = Google::class;

    /**
     * Return products.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = self::DEFAULT_PAGER_LIMIT)
    {
        $qb = $this->createQueryBuilder('g')
            ->orderBy('g.id', 'DESC')
        ;     

        if (isset($query['availability'])) {
            $qb->andWhere('g.g_availability = :availability')
                ->setParameter('availability', $query['availability']);                
        }

        if (isset($query['sku'])) {
            $qb->innerJoin('g.parent', 'p')
                ->andWhere('p.sku = :sku')
                ->setParameter('sku', $query['sku']);                
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
