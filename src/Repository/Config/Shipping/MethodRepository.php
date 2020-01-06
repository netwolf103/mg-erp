<?php

namespace App\Repository\Config\Shipping;

use App\Entity\Config\Shipping\Method;

use App\Repository\AbstractRepository;

/**
 * Repository for shipping method config.
 * 
 * @method Method|null find($id, $lockMode = null, $lockVersion = null)
 * @method Method|null findOneBy(array $criteria, array $orderBy = null)
 * @method Method[]    findAll()
 * @method Method[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MethodRepository extends AbstractRepository
{
	protected $entityClass = Method::class;

    /**
     * Return products.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
        ;

        foreach ($query as $field => $val) {
            $qb->andWhere(sprintf('m.%s LIKE :%s', $field, $field))
                ->setParameter($field, '%' . $val . '%');
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
