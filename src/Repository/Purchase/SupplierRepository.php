<?php

namespace App\Repository\Purchase;

use App\Entity\Supplier;

use App\Repository\AbstractRepository;

/**
 * Repository for purchase supplier.
 *
 * @method Supplier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supplier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supplier[]    findAll()
 * @method Supplier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SupplierRepository extends AbstractRepository
{
    protected $entityClass = Supplier::class;

    /**
     * Return suppliters.
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

        foreach ($query as $field => $val) {
            $qb->andWhere(sprintf('s.%s LIKE :%s', $field, $field))
                ->setParameter($field, '%' . $val . '%');
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }
}
