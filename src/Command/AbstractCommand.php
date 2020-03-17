<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Command abstract class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class AbstractCommand extends Command
{
    /**
     * Containe manager.
     *
     * @var ContainerInterface
     */
    protected $container;

	/**
	 * Message bus.
	 *
	 * @var MessageBusInterface
	 */
	protected $bus;

 	/**
	 * Set MessageBusInterface object.
	 * 
	 * @param MessageBusInterface $container
	 */
	protected function setBus(MessageBusInterface $bus): self
	{
		$this->bus = $bus;

		return $this;
	}

	/**
	 * Get MessageBusInterface object.
	 * 
	 * @return MessageBusInterface
	 */
	protected function getBus(): MessageBusInterface
	{
		return $this->bus;
	}

	/**
	 * Set ContainerInterface object.
	 * 
	 * @param ContainerInterface $container
	 */
	protected function setContainer(ContainerInterface $container): self
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * Get ContainerInterface object.
	 * 
	 * @return ContainerInterface
	 */
	protected function getContainer(): ContainerInterface
	{
		return $this->container;
	}

	/**
	 * Get ManagerRegistry object.
	 * 
	 * @return ManagerRegistry
	 */
	protected function getDoctrine(): ManagerRegistry
	{
		return $this->container->get('doctrine');
	}

    /**
     * Dispatches a message to the bus.
     *
     * @param object|Envelope $message The message or the message pre-wrapped in an envelope
     *
     * @final
     */
	protected function dispatchMessage($message)
	{
		return $this->bus->dispatch($message);
	}			 	
}