<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Event class of controller.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ControllerEvent extends Event
{
	/**
	 * Name of data verify event.
	 */
	const DATA_VERIFY = 'controller.data.verify';

	/**
	 * ServiceEntityRepository object list.
	 *
	 * @var array
	 */
	private $objects = [];

	/**
	 * Initialize ServiceEntityRepository object.
	 * 
	 * @param array $objects
	 */
	public function __construct(array $objects)
	{
		$this->objects = $objects;
	}

	/**
	 * Get ServiceEntityRepository object list.
	 * 
	 * @return array
	 */
	public function getObjects(): array
	{
		return $this->objects;
	}

	/**
	 * Set ServiceEntityRepository object list.
	 * 
	 * @param array $objects
	 */
	public function setObjects(array $objects)
	{
		$this->objects = $objects;

		return $this;
	}

	/**
	 * Get a ServiceEntityRepository object.
	 * 
	 * @param  string $entity
	 * @return null|ServiceEntityRepository
	 */
	public function getObject(string $entity)
	{
		return $this->objects[$entity] ?? null;
	}
}