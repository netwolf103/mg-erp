<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Related;
use App\Repository\AbstractRepository;

/**
 * Sale related order repository.
 * 
 * @method Related|null find($id, $lockMode = null, $lockVersion = null)
 * @method Related|null findOneBy(array $criteria, array $orderBy = null)
 * @method Related[]    findAll()
 * @method Related[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RelatedRepository extends AbstractRepository
{
    protected $entityClass = Related::class;
}
