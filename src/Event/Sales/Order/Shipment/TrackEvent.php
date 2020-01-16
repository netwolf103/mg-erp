<?php

namespace App\Event\Sales\Order\Shipment;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Event\Sales\OrderEvent;
use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Shipment;
use App\Entity\Sales\Order\Shipment\Track;

/**
 * Event class or shipment track.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TrackEvent extends OrderEvent
{
	/**
	 * Name of add shipment track event.
	 */
	const PREADD = 'sales.order.shipment.track.preadd';

	/**
	 * Parameter object.
	 * 
	 * @var ParameterBagInterface
	 */
	private $parameter;

	/**
	 * Shipment Entity.
	 *
	 * @var Shipment
	 */
	private $shipmentEntity;

	/**
	 * Track Entity.
	 *
	 * @var Track
	 */
	private $shipmentTrackEntity;	

	/**
	 * Initialize UserInterface, EntityManager, SaleOrder, Track, ParameterBagInterface
	 *
	 * @param UserInterface			 $user
	 * @param EntityManagerInterface $manager
	 * @param SaleOrder              $order
	 * @param Track 				 $track
	 * @param ParameterBagInterface  $parameter
	 */
	public function __construct(UserInterface $user, EntityManagerInterface $manager, SaleOrder $order, Track $track, ParameterBagInterface $parameter)
	{
		parent::__construct($user, $manager, $order);

		$this->parameter = $parameter;
		$this->shipmentTrackEntity = $track;
	}

	/**
	 * Set parameter object.
	 * 
	 * @param ParameterBagInterface $parameter
	 */
	public function setParameter(ParameterBagInterface $parameter): self
	{
		$this->parameter = $parameter;

		return $this;
	}

	/**
	 * Get parameter object.
	 * 
	 * @return ParameterBagInterface
	 */
	public function getParameter(): ParameterBagInterface
	{
		return $this->parameter;
	}

	/**
	 * Set Shipment Entity
	 * 
	 * @param Shipment $shipmentEntity
	 */
	public function setShipmentEntity(Shipment $shipmentEntity): self
	{
		$this->shipmentEntity = $shipmentEntity;

		return $this;
	}

	/**
	 * Get Shipment Entity
	 * 
	 * @return Shipment
	 */
	public function getShipmentEntity(): ?Shipment
	{
		return $this->shipmentEntity;
	}

	/**
	 * Set Track Entity
	 * 
	 * @param SaleOrderShipment $shipmentTrackEntity
	 */
	public function setShipmentTrackEntity(Track $shipmentTrackEntity): self
	{
		$this->shipmentTrackEntity = $shipmentTrackEntity;

		return $this;
	}

	/**
	 * Get Track Entity
	 * 
	 * @return Track
	 */
	public function getShipmentTrackEntity(): ?Track
	{
		return $this->shipmentTrackEntity;
	}	
}