<?php

namespace App\Repository\User;

use App\Entity\User\LoginHistory;
use App\Repository\AbstractRepository;

/**
 * Repository class of user login history.
 * 
 * @method LoginHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginHistory[]    findAll()
 * @method LoginHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginHistoryRepository extends AbstractRepository
{
    protected $entityClass = LoginHistory::class;
}