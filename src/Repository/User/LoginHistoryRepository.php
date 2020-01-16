<?php

namespace App\Repository\User;

use App\Entity\User\LoginHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Repository class of user login history.
 * 
 * @method LoginHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LoginHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LoginHistory[]    findAll()
 * @method LoginHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoginHistoryRepository extends ServiceEntityRepository
{
    protected $entityClass = LoginHistory::class;
}
