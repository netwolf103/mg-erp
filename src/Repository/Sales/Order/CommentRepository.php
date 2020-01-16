<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Comment;

use App\Repository\AbstractRepository;

/**
 * Repository class of order comment.
 * 
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CommentRepository extends AbstractRepository
{
	protected $entityClass = Comment::class;

    /**
     * Return order comments.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = 20)
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.parent', 'o')
            ->orderBy('c.id', 'DESC');
        ;

        if (isset($query['status'])) {
            $qb->andWhere('c.status = :status')
                ->setParameter('status', $query['status']);
        } 

        if (isset($query['increment_id'])) {
            $qb->andWhere('o.increment_id = :increment_id')
                ->setParameter('increment_id', $query['increment_id']);
        }                              

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
