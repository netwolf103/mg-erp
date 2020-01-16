<?php

namespace App\Event\Sales\Order;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Event\Sales\OrderEvent;

use App\Entity\Product;
use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Item;

use App\Repository\Product\Option\DropdownRepository;

/**
 * Event class of order item.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemEvent extends OrderEvent
{
	/**
	 * Name of update event.
	 */
	const UPDATE = 'sales.order.item.update';

	/**
	 * Name of remove event.
	 */	
	const REMOVE = 'sales.order.item.remove';

	/**
	 * Name of cancel event.
	 */	
	const CANCEL = 'sales.order.item.cancel';

	/**
	 * Name of refund event.
	 */		
	const REFUND = 'sales.order.item.refund';

	/**
	 * Name of add event.
	 */	
	const NEW = 'sales.order.item.new';

	/**
	 * Name of exchange event.
	 */
	const EXCHANGE = 'sales.order.item.exchange';

	/**
	 * Origin Item object
	 * 
	 * @var Item
	 */
	protected $originItem;

	/**
	 * Item object
	 * 
	 * @var Item
	 */
	protected $item;

	/**
	 * DropdownRepository object
	 * 
	 * @var DropdownRepository
	 */
	protected $dropdownRepository;	

	/**
	 * Post data.
	 * 
	 * @var array
	 */
	protected $postData = [];

	/**
	 * Initialize UserInterface, EntityManager, SaleOrder, Item
	 *
	 * @param UserInterface			 $user
	 * @param EntityManagerInterface $manager
	 * @param SaleOrder              $order
	 * @param Item       	 		 $item
	 * @param DropdownRepository     $dropdownRepository
	 * @param array     			 $postData
	 * @param Item     			 	 $originItem
	 */
	public function __construct(
		UserInterface $user, 
		EntityManagerInterface $manager, 
		SaleOrder $order, 
		Item $item, 
		DropdownRepository $dropdownRepository = null, 
		array $postData = [],
		Item $originItem = null
	)
	{
		parent::__construct($user, $manager, $order);

		$this->originItem			= $originItem;
		$this->item 				= $item;
		$this->dropdownRepository 	= $dropdownRepository;
		$this->postData 			= $postData;
	}

	/**
	 * Get origin item.
	 * 
	 * @return Item
	 */
	public function getOriginItem(): Item
	{
		return $this->originItem;
	}

	/**
	 * Set origin item.
	 * 
	 * @param Item $originItem
	 */
	public function setOriginItem(Item $originItem): self
	{
		$this->originItem = $originItem;

		return $this;
	}

	/**
	 * Get Item
	 * 
	 * @return Item
	 */
	public function getItem(): Item
	{
		return $this->item;
	}

	/**
	 * Set Item
	 * 
	 * @param Item $item
	 */
	public function setItem(Item $item): self
	{
		$this->item = $item;

		return $this;
	}

	/**
	 * Get DropdownRepository
	 * 
	 * @return array
	 */
	public function getDropdownRepository(): DropdownRepository
	{
		return $this->dropdownRepository;
	}

	/**
	 * Set DropdownRepository
	 * 
	 * @param Product $dropdownRepository
	 */
	public function setDropdownRepository(DropdownRepository $dropdownRepository): self
	{
		$this->dropdownRepository = $dropdownRepository;

		return $this;
	}		

	/**
	 * Get post data
	 * 
	 * @return array
	 */
	public function getPostData(): array
	{
		return $this->postData;
	}

	/**
	 * Set post data.
	 * 
	 * @param array $postData
	 */
	public function setPostData(array $postData): self
	{
		$this->postData = $postData;

		return $this;
	}				
}