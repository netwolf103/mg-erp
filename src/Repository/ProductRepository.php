<?php

namespace App\Repository;

use App\Entity\Product;

use App\Repository\AbstractRepository;

/**
 * Repository class of product.
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductRepository extends AbstractRepository
{
    protected $entityClass = Product::class;

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
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
        ;

        if (isset($query['created_at'])) {
            $from = ($query['created_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['created_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);

            $qb->andWhere('p.created_at >= :from')
                ->andWhere('p.created_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }      

        if (isset($query['updated_at'])) {
            $from = ($query['updated_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['updated_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);            

            $qb->andWhere('p.updated_at >= :from')
                ->andWhere('p.updated_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }        

        if (isset($query['sku'])) {
            $qb->andWhere('p.sku = :sku')
                ->setParameter('sku', $query['sku']);
        }

        if (isset($query['name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $query['name'] . '%');
        }

        if (isset($query['status'])) {
            $qb->andWhere('p.status = :status')
                ->setParameter('status', $query['status']);
        }

        if (isset($query['product_line'])) {
            switch ($query['product_line']) {
                case Product::LINE_VIRTUAL:
                    $qb->andWhere('p.type_id = :type_id')
                        ->setParameter('type_id', Product::TYPE_VIRTUAL_FLAG);
                    break;

                case Product::LINE_PURCHASE:
                    $qb->andWhere('p.purchase_url IS NOT NULL');
                    break;

                case Product::LINE_FACTORY:
                    $qb->innerJoin('p.supplier', 'supplier')
                        ->andWhere('supplier.id IS NOT NULL')
                    ;
                    break;

                case Product::LINE_UNDEFINED:
                    $qb->andWhere('p.type_id != :type_id')
                        ->setParameter('type_id', Product::TYPE_VIRTUAL_FLAG)
                        ->andWhere('p.purchase_url IS NULL')               
                        ->leftJoin('p.supplier', 'supplier')
                        ->andWhere('supplier.id IS NULL')
                    ;
                    break;                                                            
            }
        }        

        if (isset($query['hasSample'])) {
            $where = 'p.has_sample = :hasSample';
            if ($query['hasSample'] == 0) {
                $where .= ' OR p.has_sample IS NULL';
            }
            
            $qb->andWhere($where)
                ->setParameter('hasSample', $query['hasSample']);
        }        

        if (isset($query['google'])) {
            $qb->leftJoin('p.google', 'g');

            if ($query['google'] == 'no') {
                $qb->andWhere('g.id IS NULL');
            } elseif ($query['google'] == 'yes') {
                $qb->andWhere('g.id IS NOT NULL');
            }
        }

        if (isset($query['catalogInventory'])) {
            $qb->innerJoin('p.catalogInventory', 'ci')
                ->andWhere('ci.is_in_stock = :is_in_stock')
                ->setParameter('is_in_stock', $query['catalogInventory'])
            ;
        }                

        return $this->createPaginator($qb, $currentPage, $limit);
    }
}
