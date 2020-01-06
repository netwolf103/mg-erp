<?php

namespace App\Event\Sales\Order;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Event\AbstractEvent;

use App\Entity\SaleOrder;

/**
 * Sale order shipping event.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShippingEvent extends AbstractEvent
{
	/**
	 * Name of update event.
	 */
	const UPDATE = 'sales.order.shipping.update';

	/**
	 * Old shipping
	 * 
	 * @var SaleOrder
	 */
	protected $oldShipping;

	/**
	 * New shipping
	 * 
	 * @var SaleOrder
	 */
	protected $newShipping;

	/**
	 * Initialize UserInterface, EntityManager, oldShipping, newShipping
	 *
	 * @param UserInterface			 $user
	 * @param EntityManagerInterface $manager
	 * @param SaleOrder              $oldShipping
	 * @param SaleOrder              $newShipping
	 */
	public function __construct(UserInterface $user, EntityManagerInterface $manager, SaleOrder $oldShipping, SaleOrder $newShipping)
	{
		parent::__construct($user, $manager);

		$this->oldShipping = $oldShipping;
		$this->newShipping = $newShipping;
	}

	/**
	 * Set old order
	 * 
	 * @param SaleOrder
	 */
	public function getOldShipping(): SaleOrder
	{
		return $this->oldShipping;
	}

	/**
	 * Set old order
	 * 
	 * @param SaleOrder $oldShipping
	 */
	public function setOldShipping(SaleOrder $oldShipping)
	{
		$this->oldShipping = $oldShipping;

		return $this;
	}

	/**
	 * Set new order
	 * 
	 * @param SaleOrder
	 */
	public function getNewShipping(): SaleOrder
	{
		return $this->newShipping;
	}

	/**
	 * Set new order
	 * 
	 * @param SaleOrder $newShipping
	 */
	public function setNewShipping(SaleOrder $newShipping)
	{
		$this->newShipping = $newShipping;

		return $this;
	}						
}