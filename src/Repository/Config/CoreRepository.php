<?php

namespace App\Repository\Config;

use App\Entity\Config\Core;
use App\Repository\AbstractRepository;

/**
 * Repository for core config.
 * 
 * @method Core|null find($id, $lockMode = null, $lockVersion = null)
 * @method Core|null findOneBy(array $criteria, array $orderBy = null)
 * @method Core[]    findAll()
 * @method Core[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CoreRepository extends AbstractRepository
{
    protected $entityClass = Core::class;

    /**
     * Return config value by path.
     * 
     * @param  string $path
     * @return string
     */
    public function getValue(string $path)
    {
    	return $this->findOneBy(['path' => $path]);
    }
}
