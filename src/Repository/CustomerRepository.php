<?php

namespace App\Repository;

use App\Entity\Customer;

use App\Repository\AbstractRepository;

/**
 * Repository class of customer.
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends AbstractRepository
{
    protected $entityClass = Customer::class;

    /**
     * Return customers.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC')
        ;

        foreach ($query as $field => $val) {
            $qb->andWhere(sprintf('c.%s LIKE :%s', $field, $field))
                ->setParameter($field, '%' . $val . '%');
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }

    public function countAll()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total')
            ->getQuery()
            ->getSingleScalarResult();           
    }

    public function countToday()
    {
        $datetime = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total')
            ->where('c.created_at >= :start')
            ->andWhere('c.created_at <= :end')
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

        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total')
            ->where('c.created_at >= :start')
            ->andWhere('c.created_at <= :end')
            ->setParameter('start', $start->format('Y-m-d 00:00:00'))
            ->setParameter('end', $end->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countMonth()
    {
        $datetime = new \DateTimeImmutable();

        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as total')
            ->where('c.created_at >= :start')
            ->andWhere('c.created_at <= :end')
            ->setParameter('start', $datetime->format('Y-m-01 00:00:00'))
            ->setParameter('end', $datetime->format('Y-m-d h:i:s'))
            ->getQuery()
            ->getSingleScalarResult();        
    }        
}
