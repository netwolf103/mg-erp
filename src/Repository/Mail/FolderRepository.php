<?php

namespace App\Repository\Mail;

use App\Entity\Mail\Folder;

use App\Repository\AbstractRepository;

/**
 * Repository class of mail folder.
 * 
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends AbstractRepository
{
    protected $entityClass = Folder::class;

    /**
     * Return mails.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = self::DEFAULT_PAGER_LIMIT)
    {
        $qb = $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
        ;

        foreach ($query as $field => $val) {
            $qb->andWhere(sprintf('f.%s LIKE :%s', $field, $field))
                ->setParameter($field, '%' . $val . '%');
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }

    /**
     * Return effective folders.
     *
     * @return array
     */
    public function getAllEffective(): array
    {
        $qb = $this->createQueryBuilder('f')
            ->where('f.isPause = 0')
            ->orWhere('f.isPause IS NULL')
            ->orderBy('f.id', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
}