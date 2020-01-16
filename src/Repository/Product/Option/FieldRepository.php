<?php

namespace App\Repository\Product\Option;

use App\Entity\Product\Option\Field;

use App\Repository\AbstractRepository;

/**
 * Repository class of product field option.
 * 
 * @method Field|null find($id, $lockMode = null, $lockVersion = null)
 * @method Field|null findOneBy(array $criteria, array $orderBy = null)
 * @method Field[]    findAll()
 * @method Field[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class FieldRepository extends AbstractRepository
{
	protected $entityClass = Field::class;
}
