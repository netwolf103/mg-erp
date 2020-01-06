<?php

namespace App\Repository\Product\Purchase\Order;

use App\Entity\Product\Purchase\Order\Item;
use App\Repository\AbstractRepository;

/**
 * Repository for product purchase order items.
 * 
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemRepository extends AbstractRepository
{
    protected $entityClass = Item::class;
}
