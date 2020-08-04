<?php

namespace App\Repository\Newsletter;

use App\Entity\Newsletter\Subscriber;

use App\Repository\AbstractRepository;

/**
 * Repository class of newsletter subscriber.
 * 
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * 
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SubscriberRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */    
    protected $entityClass = Subscriber::class;

    /**
     * Return subscribers.
     *
     * @param  array       $query
     * @param  int|integer $currentPage
     * @param  int|integer $limit
     * @return array
     */
    public function getAll(array $query = [], int $currentPage = 1, int $limit = self::DEFAULT_PAGER_LIMIT)
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
        ;

        if (isset($query['email'])) {
            $qb->andWhere('s.subscriber_email LIKE :email')
                ->setParameter('email', '%' . $query['email'] . '%');
        }

        if (isset($query['customer_firstname'])) {
            $qb->andWhere('s.customer_firstname LIKE :customer_firstname')
                ->setParameter('customer_firstname', '%' . $query['customer_firstname'] . '%');
        }

        if (isset($query['customer_middlename'])) {
            $qb->andWhere('s.customer_middlename LIKE :customer_middlename')
                ->setParameter('customer_middlename', '%' . $query['customer_middlename'] . '%');
        }

        if (isset($query['customer_lastname'])) {
            $qb->andWhere('s.customer_lastname LIKE :customer_lastname')
                ->setParameter('customer_lastname', '%' . $query['customer_lastname'] . '%');
        }                        

        if (isset($query['type'])) {
            $qb->andWhere('s.type = :type')
                ->setParameter('type', $query['type']);
        }

        if (isset($query['subscriber_status'])) {
            $qb->andWhere('s.subscriber_status = :subscriber_status')
                ->setParameter('subscriber_status', $query['subscriber_status']);
        }

        return $this->createPaginator($qb, $currentPage, $limit);
    }    
}
