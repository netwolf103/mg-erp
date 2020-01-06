<?php

namespace App\Repository\Product\Purchase\Order;

use App\Entity\Product\Purchase\Order\Comment;
use App\Repository\AbstractRepository;

/**
 * Repository for product purchase order comments.
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
}
