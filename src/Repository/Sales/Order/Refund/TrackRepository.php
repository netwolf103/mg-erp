<?php

namespace App\Repository\Sales\Order\Refund;

use App\Entity\Sales\Order\Refund\Track;

use App\Repository\AbstractRepository;

/**
 * Repository for order refund track.
 * 
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TrackRepository extends AbstractRepository
{
    protected $entityClass = Track::class;
}
