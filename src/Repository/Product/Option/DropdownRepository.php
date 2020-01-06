<?php

namespace App\Repository\Product\Option;

use App\Entity\Product\Option\Dropdown;

use App\Repository\AbstractRepository;

/**
 * Repository for product dropdown option.
 *
 * @method Dropdown|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dropdown|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dropdown[]    findAll()
 * @method Dropdown[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class DropdownRepository extends AbstractRepository
{
	protected $entityClass = Dropdown::class;
}
