<?php

namespace App\Repository\Product;

use App\Entity\Product\Media;

use App\Repository\AbstractRepository;

/**
 * Repository class of product media.
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MediaRepository extends AbstractRepository
{
	protected $entityClass = Media::class;
}
