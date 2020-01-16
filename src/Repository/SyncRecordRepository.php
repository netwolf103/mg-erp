<?php

namespace App\Repository;

use App\Entity\SyncRecord;

use App\Repository\AbstractRepository;

/**
 * Repository class of sync record.
 *
 * @method SyncRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method SyncRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method SyncRecord[]    findAll()
 * @method SyncRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SyncRecordRepository extends AbstractRepository
{
	protected $entityClass = SyncRecord::class;
}
