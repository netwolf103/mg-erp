<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use LogicException;

/**
 * Abstract repository.
 *
 * @method find($id, $lockMode = null, $lockVersion = null)
 * @method findOneBy(array $criteria, array $orderBy = null)
 * @method findAll()
 * @method findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
	/**
	 * Default page limit.
	 */
	const DEFAULT_PAGER_LIMIT = 20;

	/**
	 * Entity class.
	 * 
	 * @var object
	 */
	protected $entityClass;

    /**
     * Init entity instance.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
    	if (is_null($this->entityClass)) {
            throw new LogicException('Object property "entityClass" is null.');    		
    	}

    	parent::__construct($registry, $this->entityClass);
    }

    /**
     * Create Paginator.
     *
     * @param  QueryBuilder $queryBuilder
     * @param  int          $currentPage
     * @param  int|integer  $pageSize
     * @return array
     */
    protected function createPaginator(QueryBuilder $queryBuilder, int $currentPage, int $pageSize = self::DEFAULT_PAGER_LIMIT)
    {
        $currentPage = $currentPage < 1 ? 1 : $currentPage;
        $firstResult = ($currentPage - 1) * $pageSize;

        $query = $queryBuilder
            ->setFirstResult($firstResult)
            ->setMaxResults($pageSize)
            ->getQuery();

        $paginator = new Paginator($query);
        $numResults = $paginator->count();
        $hasPreviousPage = $currentPage > 1;
        $hasNextPage = ($currentPage * $pageSize) < $numResults;

        return [
            'results' => $paginator->getIterator(),
            'numResults' => $numResults,
            'currentPage' => $currentPage,
            'hasPreviousPage' => $hasPreviousPage,
            'hasNextPage' => $hasNextPage,
            'previousPage' => $hasPreviousPage ? $currentPage - 1 : null,
            'nextPage' => $hasNextPage ? $currentPage + 1 : null,
            'numPages' => (int) ceil($numResults / $pageSize),
            'haveToPaginate' => $numResults > $pageSize,
        ];
    }        
}