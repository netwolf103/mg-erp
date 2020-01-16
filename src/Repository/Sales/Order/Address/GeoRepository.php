<?php

namespace App\Repository\Sales\Order\Address;

use App\Entity\Sales\Order\Address\Geo;
use App\Repository\AbstractRepository;

/**
 * Repository class of order address geo.
 * 
 * @method Geo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Geo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Geo[]    findAll()
 * @method Geo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 *  @author Zhang Zhao <netwolf103@gmail.com>
 */
class GeoRepository extends AbstractRepository
{
    protected $entityClass = Geo::class;
}
