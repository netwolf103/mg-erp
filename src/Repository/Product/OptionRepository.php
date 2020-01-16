<?php

namespace App\Repository\Product;

use App\Entity\Product\Option;

use App\Repository\AbstractRepository;

/**
 * Repository class of product option.
 * 
 * @method ProductOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductOption[]    findAll()
 * @method ProductOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OptionRepository extends AbstractRepository
{
	protected $entityClass = Option::class;
}
