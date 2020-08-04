<?php

namespace App\Repository;

use App\Entity\Mail;
use App\Repository\AbstractRepository;

/**
 * Repository class of mail.
 *
 * @method Mail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mail[]    findAll()
 * @method Mail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */    
    protected $entityClass = Mail::class;

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
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.date', 'DESC')
        ;

        if (isset($query['date'])) {
            $from = ($query['date']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['date']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);            

            $qb->andWhere('m.date >= :from')
                ->andWhere('m.date <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }

        if (isset($query['created_at'])) {
            $from = ($query['created_at']['from'] ?? date('Y-m-d')) ?: date('Y-m-d');
            $to = ($query['created_at']['to'] ?? date('Y-m-d')) ?: date('Y-m-d');

            $from = sprintf('%s 00:00:01', $from);
            $to = sprintf('%s 23:23:59', $to);            

            $qb->andWhere('m.created_at >= :from')
                ->andWhere('m.created_at <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }        

        if (isset($query['from'])) {
            $qb->andWhere('m.fromName LIKE :fromName')
                ->setParameter('fromName', '%' . $query['from'] . '%');
        }

        if (isset($query['subject'])) {
            $qb->andWhere('m.subject LIKE :subject')
                ->setParameter('subject', '%' . $query['subject'] . '%');
        }

        if (isset($query['folder'])) {
            $qb->innerJoin('m.folder', 'f')
                ->andWhere('f.id = :id')
                ->setParameter('id', $query['folder']);
        }        

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
