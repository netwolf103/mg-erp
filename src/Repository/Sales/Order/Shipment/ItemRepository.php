<?php

namespace App\Repository\Sales\Order\Shipment;

use App\Entity\Sales\Order\Shipment\Item;

use App\Repository\AbstractRepository;

/**
 * Repository for order shipment item.
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
