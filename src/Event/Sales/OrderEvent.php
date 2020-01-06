<?php

namespace App\Event\Sales;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Event\AbstractEvent;

use App\Entity\SaleOrder;

/**
 * Sale order event.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderEvent extends AbstractEvent
{
	/**
	 * SaleOrder object
	 * 
	 * @var SaleOrder
	 */
	protected $order;

	/**
	 * Initialize User, EntityManager, SaleOrder
	 * 
	 * @param UserInterface			 $user
	 * @param EntityManagerInterface $manager
	 * @param SaleOrder              $order
	 */
	public function __construct(UserInterface $user, EntityManagerInterface $manager, SaleOrder $order)
	{
		parent::__construct($user, $manager);
		
		$this->order = $order;
	}

	/**
	 * Get SaleOrder.
	 * 
	 * @return SaleOrder
	 */
	public function getOrder(): SaleOrder
	{
		return $this->order;
	}

	/**
	 * Set SaleOrder
	 * 
	 * @param SaleOrder $order
	 */
	public function setOrder(SaleOrder $order)
	{
		$this->order = $order;

		return $this;
	}	
}