<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Abstract event class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class AbstractEvent extends Event
{
	/**
	 * UserInterface object
	 *
	 * @var UserInterface
	 */
	protected $user;

	/**
	 * EntityManager object
	 *
	 * @var EntityManagerInterface
	 */
	protected $manager;

	/**
	 * Initialize User, EntityManager
	 * 
	 * @param UserInterface			 $user
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(UserInterface $user, EntityManagerInterface $manager)
	{
		$this->user = $user;
		$this->manager = $manager;
	}

	/**
	 * Get UserInterface.
	 *
	 * @return UserInterface
	 */
	public function getUser(): UserInterface
	{
		return $this->user;
	}

	/**
	 * Set UserInterface.
	 *
	 * @param UserInterface $user
	 */
	public function setUser(UserInterface $user)
	{
		$this->user = $user;

		return $this;
	}		

	/**
	 * Get EntityManager.
	 *
	 * @return EntityManagerInterface
	 */
	public function getManager(): EntityManagerInterface
	{
		return $this->manager;
	}

	/**
	 * Set EntityManager.
	 *
	 * @param EntityManagerInterface $order
	 */
	public function setManager(EntityManagerInterface $manager)
	{
		$this->manager = $manager;

		return $this;
	}	
}