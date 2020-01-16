<?php

namespace App\Event\Sales\Order;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Event\AbstractEvent;

use App\Entity\Sales\Order\Address;

/**
 * Event class of order address.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressEvent extends AbstractEvent
{
	/**
	 * Name of update event.
	 */
	const UPDATE = 'sales.order.address.update';

	/**
	 * Old address
	 * 
	 * @var Address
	 */
	protected $oldAddress;

	/**
	 * New address
	 * 
	 * @var Address
	 */
	protected $newAddress;	

	/**
	 * Initialize UserInterface, EntityManager, Address
	 *
	 * @param UserInterface			 $user
	 * @param EntityManagerInterface $manager
	 * @param Address                $oldAddress
	 * @param Address       		 $newAddress
	 */
	public function __construct(UserInterface $user, EntityManagerInterface $manager, Address $oldAddress, Address $newAddress)
	{
		parent::__construct($user, $manager);

		$this->oldAddress = $oldAddress;
		$this->newAddress = $newAddress;
	}	

	/**
	 * Get old address
	 * 
	 * @return Address
	 */
	public function getOldAddress(): Address
	{
		return $this->oldAddress;
	}

	/**
	 * Set old address
	 * 
	 * @param Address $address
	 */
	public function setOldAddress(Address $address): self
	{
		$this->oldAddress = $address;

		return $this;
	}

	/**
	 * Get new address
	 * 
	 * @return Address
	 */
	public function getNewAddress(): Address
	{
		return $this->newAddress;
	}

	/**
	 * Set new address
	 * 
	 * @param Address $address
	 */
	public function setNewAddress(Address $address): self
	{
		$this->newAddress = $address;

		return $this;
	}	
}