<?php

namespace App\Repository;

use App\Entity\User;

use App\Repository\AbstractRepository;

/**
 * User respository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class UserRepository extends AbstractRepository
{
    protected $entityClass = User::class;

    /**
     * Return users.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = self::DEFAULT_PAGER_LIMIT)
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
        ;

        foreach ($query as $field => $val) {
            $qb->andWhere(sprintf('u.%s LIKE :%s', $field, $field))
                ->setParameter($field, '%' . $val . '%');
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }
}
