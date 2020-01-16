<?php

namespace App\Repository\Sales\Order;

use App\Entity\Sales\Order\Payment;

use App\Repository\AbstractRepository;

/**
 * Repository class of order payment.
 * 
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PaymentRepository extends AbstractRepository
{
	protected $entityClass = Payment::class;
}
