<?php

namespace App\Repository\Product;

use App\Entity\Product\CatalogInventory;
use App\Repository\AbstractRepository;

/**
 * Repository class of catalog inventory.
 * 
 * @method CatalogInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogInventory[]    findAll()
 * @method CatalogInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CatalogInventoryRepository extends AbstractRepository
{
    protected $entityClass = CatalogInventory::class;
}
