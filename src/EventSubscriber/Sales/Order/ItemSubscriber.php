<?php

namespace App\EventSubscriber\Sales\Order;

use App\Entity\Sales\Order\Item;
use App\Entity\Product;
use App\Entity\Product\Option;
use App\Entity\Product\Option\Field;
use App\Entity\Product\Option\Dropdown;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\Catalog\Category\Product\Alert;

/**
 * Subscriber of item.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemSubscriber implements EventSubscriber
{
    /**
     * Message bus.
     * 
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

	/**
     * Subscribe to the event list.
     * 
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * {@inheritdoc}
     */  
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    /**
     * {@inheritdoc}
     */  
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }    

    /**
     * Item type classification
     *
     * @param  LifecycleEventArgs $args
     * @return void
     */
    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Item) {
            // 虚拟产品
            if ($entity->getIsVirtual()) {
                $entity->setItemType(Item::ITEM_TYPE_VIRTUAL);
                return $this;
            }

            $productEntity = $entity->getProduct();

            // 计算当前库存
            $inventory = 0;
            if (!$productEntity->getHasOptions()) {
                $inventory = $productEntity->getInventory();
            } else {
                $itemOptions = $entity->productOptionsUnserialize();
                $itemOptions = $itemOptions['options'] ?? [];

                foreach ($itemOptions as $_itemOption) {
                    switch ($_itemOption['option_type']) {
                        case Field::OPTION_TYPE:
                            break;
                        
                        case Dropdown::OPTION_TYPE:
                            $optionValue = $_itemOption['value'];
                            $optionValue = $productEntity->getOptionSizes()->filter(function(Dropdown $option) use ($optionValue){
                                return $option->getTitle() == $optionValue;
                            });

                            $optionValue = $optionValue->isEmpty() ? false : $optionValue->first();
                            $inventory = 0;
                            if ($optionValue) {
                                if ($parentOptionValue = $optionValue->getParentOption()) {
                                    $inventory = $parentOptionValue->getInventory();
                                    $parentOptionValue->setInventory($parentOptionValue->getInventory() - $entity->getQtyOrdered());

                                    $optionValue->setInventory($parentOptionValue->getInventory());
                                } else {
                                    $inventory = $optionValue->getInventory();
                                    $optionValue->setInventory($optionValue->getInventory() - $entity->getQtyOrdered());
                                }

                                // Low inventory alert
                                if ($optionValue->getInventoryLow()) {
                                    if ($optionValue->getInventory() <= $optionValue->getInventoryLow()) {
                                        $this->bus->dispatch(new Alert($optionValue->getId()));
                                    }
                                }
                            }
                            break;
                    }
                }
            }

            // 库存产品
            if ($inventory >= $entity->getQtyOrdered()) {
                $entity->setItemType(Item::ITEM_TYPE_STOCK);
                return $this;
            }

            // 采购产品
            if ($productEntity->getPurchaseUrl()) {
                $entity->setItemType(Item::ITEM_TYPE_PURCHASE);
                return $this;
            }

            // 工厂产品
            $entity->setItemType(Item::ITEM_TYPE_FACTORY);         
        }
    }       
}