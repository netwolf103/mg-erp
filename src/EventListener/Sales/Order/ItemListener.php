<?php

namespace App\EventListener\Sales\Order;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Intl\Intl;
use App\Currency\Rates;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Comment;
use App\Entity\Sales\Order\Refund;
use App\Entity\Sales\Order\Refund\Track;
use App\Repository\Product\Option\DropdownRepository;

/**
 * Event listener class of order item.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemListener
{
    private $rates;

    /**
     * Init ParameterBag
     *
     * @param ParameterBagInterface $parameter
     */
    public function __construct(ParameterBagInterface $parameter)
    {
        $this->rates = new Rates($parameter);
    }

    /**
     * @param App\Event\Sales\Order\ItemEvent|Event $event
     * @return void
     */
    public function onSalesOrderItemUpdate(Event $event): void
    {
        $productEntity = $event->getItem()->getProduct();

        $this
            ->setItemBase($event)    
            ->getOrder()
            ->addItem($event->getItem())
        ;

        $commentEntity = $this->getCommentEntity($event, '产品更新', $event->getPostData());

        $event->getManager()->persist($commentEntity);        

        $order = $this->updateOrder($event->getOrder());

        $event->getManager()->persist($commentEntity);
        $event->getManager()->persist($order);
    }

    /**
     * @param  App\Event\Sales\Order\ItemEvent|Event $event
     * @return void
     */
    public function onSalesOrderItemNew(Event $event): void
    {
        $productEntity = $event->getItem()->getProduct();

        $this
            ->setItemBase($event)
            ->getItem()     
            ->setQtyInvoiced($event->getItem()->getQtyOrdered())
            ->setCreatedAt(new \DateTimeImmutable())
        ;
        $event->getOrder()->addItem($event->getItem());

        $commentEntity = $this->getCommentEntity($event, '产品添加', $event->getPostData());

        $event->getManager()->persist($commentEntity);          

        $order = $this->updateOrder($event->getOrder());

        $event->getManager()->persist($commentEntity);
        $event->getManager()->persist($order);      
    }

    /**
     * @param  App\Event\Sales\Order\ItemEvent|Event $event
     * @return void
     * 
     * @throws \Exception
     */
    public function onSalesOrderItemExchange(Event $event):void
    {
        $item       = $event->getItem();
        $originItem = $event->getOriginItem();
        $postData   = $event->getPostData();

        // if ($originItem->getSku() == $item->getSku()) {
        //     throw new \Exception('Please choose a different sku for exchange.');
        // }

        $this
            ->setItemBase($event)
            ->getItem()     
            ->setQtyInvoiced($event->getItem()->getQtyOrdered())
            ->setCreatedAt(new \DateTimeImmutable())
        ;
        $event->getOrder()->addItem($event->getItem());

        $refundAmount   = $postData['refund_amount'] ?? 0;
        $orderStatus    = SaleOrder::ORDER_STATUS_PROCESSING;

        if ($refundAmount) {
            $qtyRefunded = $originItem->getQtyOrdered();

            $refundEntity = new Refund;
            $refundEntity->setItem($originItem);
            $refundEntity->setSku($originItem->getSku());
            $refundEntity->setName($originItem->getName());
            $refundEntity->setPrice($originItem->getPrice());
            $refundEntity->setQtyOrdered($originItem->getQtyOrdered());
            $refundEntity->setQtyRefunded($qtyRefunded);
            $refundEntity->setRowTotal($originItem->getRowTotal());
            $refundEntity->setRefundAmount($refundAmount);
            $refundEntity->setStatus(Refund::STATUS_N);
            $refundEntity->setCreatedAt(new \DateTimeImmutable());

            $carrierName = $postData['carrier_name'] ?? '';
            $trackNumber = $postData['track_number'] ?? '';

            if (!empty($carrierName) && !empty($trackNumber)) {
                $trackEntity = new Track;
                $trackEntity->setParent($refundEntity);
                $trackEntity->setCarrierName($carrierName);
                $trackEntity->setTrackNumber($trackNumber);            

                $event->getManager()->persist($trackEntity);

                $orderStatus = SaleOrder::ORDER_STATUS_RESHIPPING;
            }
            
            $event->getManager()->persist($refundEntity);
        }

        $originItem->setQtyCanceled($originItem->getQtyOrdered());
        $event->getManager()->persist($originItem);        

        $commentEntity = $this->getCommentEntity($event, '产品换货', $event->getPostData());

        $event->getManager()->persist($commentEntity);          

        $event->getOrder()->setStatus($orderStatus);
        $order = $this->updateOrder($event->getOrder());

        $event->getManager()->persist($commentEntity);
        $event->getManager()->persist($order);          
    }

    /**
     * @param  App\Event\Sales\Order\ItemEvent|Event $event
     * @return void
     */
    public function onSalesOrderItemRemove(Event $event): void
    {
        $commentEntity = $this->getCommentEntity($event, '产品删除', $event->getPostData());

        $event->getManager()->persist($commentEntity);        

        $order = $this->updateOrder($event->getOrder());

        $event->getManager()->persist($commentEntity);
        $event->getManager()->persist($order);     
        $event->getManager()->flush();     
    }

    /**
     * @param  App\Event\Sales\Order\ItemEvent|Event $event
     * @return void
     */
    public function onSalesOrderItemCancel(Event $event): void
    {
        $postData = $event->getPostData();

        $refundEntity = new Refund;
        $refundEntity->setItem($event->getItem());
        $refundEntity->setSku($event->getItem()->getSku());
        $refundEntity->setName($event->getItem()->getName());
        $refundEntity->setPrice($event->getItem()->getPrice());
        $refundEntity->setQtyOrdered($event->getItem()->getQtyOrdered());
        $refundEntity->setQtyRefunded($postData['qty_canceled']);
        $refundEntity->setRowTotal($event->getItem()->getRowTotal());
        $refundEntity->setRefundAmount($postData['refund_amount'] ?? 0);
        $refundEntity->setStatus(Refund::STATUS_N);
        $refundEntity->setCreatedAt(new \DateTimeImmutable());
                
        $commentEntity = $this->getCommentEntity($event, '产品取消', $event->getPostData());

        $event->getManager()->persist($refundEntity);         
        $event->getManager()->persist($commentEntity);         
    }

    /**
     * @param  App\Event\Sales\Order\ItemEvent|Event $event
     * @return void
     */
    public function onSalesOrderItemRefund(Event $event): void
    {
        $postData = $event->getPostData();

        $refundEntity = new Refund;
        $refundEntity->setItem($event->getItem());
        $refundEntity->setSku($event->getItem()->getSku());
        $refundEntity->setName($event->getItem()->getName());
        $refundEntity->setPrice($event->getItem()->getPrice());
        $refundEntity->setQtyOrdered($event->getItem()->getQtyOrdered());
        $refundEntity->setQtyRefunded($postData['qty_refunded']);
        $refundEntity->setRowTotal($event->getItem()->getRowTotal());
        $refundEntity->setRefundAmount($postData['refund_amount'] ?? 0);
        $refundEntity->setStatus(Refund::STATUS_N);
        $refundEntity->setCreatedAt(new \DateTimeImmutable());

        $trackEntity = new Track;
        $trackEntity->setParent($refundEntity);
        $trackEntity->setCarrierName($postData['carrier_name'] ?? '');
        $trackEntity->setTrackNumber($postData['track_number'] ?? '');

        $commentEntity = $this->getCommentEntity($event, '产品退回', $postData);

        $event->getManager()->persist($trackEntity);
        $event->getManager()->persist($refundEntity);
        $event->getManager()->persist($commentEntity);
         
    }

    /**
     * Return Comment object
     * 
     * @param  Event  $event
     * @param  string $actionMessage
     * @param  array  $postData
     * @return Comment
     */
    private function getCommentEntity(Event $event, string $actionMessage, array $postData): Comment
    {
        $message = $this->formatMessage($actionMessage, $event->getItem());
        if (isset($postData['comment']) && !empty($postData['comment'])) {
            $message .= '<br />' . $postData['comment'];
        }

        $commentEntity = new Comment;
        $commentEntity->setParent($event->getOrder());
        $commentEntity->setUser($event->getUser());
        $commentEntity->setComment($message);
        $commentEntity->setStatus($event->getOrder()->getStatus());
        $commentEntity->setEntityName('sale_order_item');
        $commentEntity->setCreatedAt(new \DateTimeImmutable());

        return $commentEntity;
    }    

    /**
     * Message format.
     * 
     * @param  string $prefix
     * @param  App\Entity\Sales\Order\Item $item
     * @return string
     */
    private function formatMessage(string $prefix, $item): string
    {
        return sprintf(
            '%s->%s: QTY[%s]', 
            $prefix, 
            $item->getSku(), 
            $item->getQtyOrdered()
        );
    }

    /**
     * Update order.
     * 
     * @param  SaleOrder $order
     * @return SaleOrder
     */
    private function updateOrder(SaleOrder $order)
    {
        $subtotal       = $base_subtotal = $grand_total = $base_grand_total = $total_qty_ordered = 0;
        $discountAmount = $baseDiscountAmount = 0;
        $item_types     = [];

        foreach ($order->getItems() as $item) {
           $subtotal            += $item->getPrice() * $item->getQtyOrdered();
           $base_subtotal       += $item->getBasePrice() * $item->getQtyOrdered();
           $total_qty_ordered   += $item->getQtyOrdered();
           $item_types[]        = $item->getItemType();

           $discountAmount      += $item->getDiscountAmount();
           $baseDiscountAmount  += $item->getBaseDiscountAmount();
        }

        $grand_total            = $order->getShippingAmount() + $subtotal - $discountAmount;
        $base_grand_total       = $order->getBaseShippingAmount() + $base_subtotal - $baseDiscountAmount;

        $order->setDiscountAmount(-$discountAmount);
        $order->setBaseDiscountAmount(-$baseDiscountAmount);
        $order->setSubtotal($subtotal);
        $order->setBaseSubtotal($base_subtotal);
        $order->setGrandTotal($grand_total);
        $order->setBaseGrandTotal($base_grand_total);
        $order->setTotalQtyOrdered($total_qty_ordered); 

        $item_types = array_unique($item_types);
        if (count($item_types) == 1) {
            $order_type = reset($item_types);
        } else {
            $order_type = SaleOrder::ORDER_TYPE_COMBINATION;
        }
        $order->setOrderType($order_type);          

        return $order;      
    }

    /**
     * Set order item options
     * 
     * @param Event $event
     */
    private function setItemBase(Event $event): Event
    {
        $postData           = $event->getPostData();
        $item               = $event->getItem();
        $productEntity      = $item->getProduct();
        $currencyCode       = $event->getOrder()->getOrderCurrencyCode();

        $customOption       = [];
        $optionBasePrice    = 0;

        if ($productEntity->getOptionSizes()) {
            $_option = $event->getDropdownRepository()->find($postData['option_size'] ?? '');

            $customOption[] = [
                'label'         => $_option->getParent()->getTitle(),
                'value'         => $_option->getTitle(),
                'print_value'   => $_option->getTitle(),
                'option_id'     => $_option->getParent()->getId(),
                'option_type'   => $_option->getParent()->getType(),
                'option_value'  => $_option->getId(),
                'custom_view'   => '',
            ];

            $optionBasePrice += $_option->getPrice();
        }

        if (($_option = $productEntity->getOptionEngravings()) && $postData['option_engravings']) {
            $customOption[] = [
                'label'         => $_option->getTitle(),
                'value'         => $postData['option_engravings'],
                'print_value'   => $postData['option_engravings'],
                'option_id'     => $_option->getId(),
                'option_type'   => $_option->getType(),
                'option_value'  => $_option->getId(),
                'custom_view'   => '',
            ];

            $optionBasePrice += $productEntity->getOptionEngravings()->getOptionValueField()->getPrice();
        }
     
        $productOptions = [
            'info_buyRequest' => [
                'options'   => $productEntity->getOptionIds(),
                'qty'       => $item->getQtyOrdered(),
            ],
            'options' => $customOption,
        ];

        $basePrice = $productEntity->getFinalPrice();

        $cartDiscountPercent = $postData['cart_discount_percent'] ?? 0;
        if ($cartDiscountPercent) {
            $basePrice -= $basePrice * ($cartDiscountPercent / 100);
        }

        if ($item->getDiscountPercent()) {
            $baseDiscountAmount = $basePrice * $item->getQtyOrdered() * ($item->getDiscountPercent() / 100);

            $item->setBaseDiscountAmount($baseDiscountAmount);
        }

        $basePrice      += $optionBasePrice;

        $price          = $this->convertPrice($basePrice, $currencyCode);
        $originalPrice  = $this->convertPrice($productEntity->getFinalPrice(), $currencyCode);
        $discountAmount = $this->convertPrice($event->getItem()->getBaseDiscountAmount(), $currencyCode);

        $rowTotal       = $price * $item->getQtyOrdered();
        $baseRowTotal   = $basePrice * $item->getQtyOrdered();

        $event->getItem()
            ->setParent($event->getOrder())
            ->setSku($productEntity->getSku())
            ->setName($productEntity->getName())
            ->setProductType($productEntity->getTypeId())
            ->setDiscountAmount($discountAmount)

            ->setPrice($price)
            ->setBasePrice($basePrice)
            ->setOriginalPrice($originalPrice)

            ->setRowTotal($rowTotal)
            ->setRowInvoiced($rowTotal)

            ->setBaseRowTotal($baseRowTotal)
            ->setBaseRowInvoiced($baseRowTotal)

            ->setProductOptions(serialize($productOptions))
            ->setUpdatedAt(new \DateTimeImmutable())
        ;

        return $event;         
    }

    /**
     * Convert price.
     * 
     * @param  float        $price
     * @param  string       $symbol
     * @return float:string
     */
    private function convertPrice(float $price, string $symbol = 'USD'): float
    {
        $price = $this->rates->convert($price, $symbol);

        return $price;
    }    
}