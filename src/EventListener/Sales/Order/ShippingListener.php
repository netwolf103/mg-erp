<?php

namespace App\EventListener\Sales\Order;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Refund;
use App\Entity\Sales\Order\Shipping\History;

/**
 * Listener of order shipping.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShippingListener
{
    private $parameter;

    public function __construct(ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * @param App\Event\Sales\Order\ShippingEvent|Event $event
     * @return void
     */
    public function onSalesOrderShippingUpdate(Event $event): void
    {
        $oldShippingEntity = $event->getOldShipping($event);
        $newShippingEntity = $event->getNewShipping($event);

        $historyEntity = new History;
        $historyEntity->setParent($newShippingEntity);
        $historyEntity->setMethod($oldShippingEntity->getShippingMethod());
        $historyEntity->setDescription($oldShippingEntity->getShippingDescription());
        $historyEntity->setAmount($oldShippingEntity->getShippingAmount());
        $historyEntity->setBaseAmount($oldShippingEntity->getBaseShippingAmount());
        $historyEntity->setComment($this->getComment($oldShippingEntity, $newShippingEntity));
        $historyEntity->setCreatedAt(new \DateTimeImmutable());


        if ($newShippingEntity->getShippingAmount() < $oldShippingEntity->getShippingAmount()) {
            $refundAmount = $oldShippingEntity->getShippingAmount() - $newShippingEntity->getShippingAmount();

            $refundEntity = new Refund;
            $refundEntity->setItem($newShippingEntity->getItems()->first());
            $refundEntity->setSku('Refund For Shipping');
            $refundEntity->setName('Refund For Shipping');
            $refundEntity->setPrice($refundAmount);
            $refundEntity->setQtyOrdered(1);
            $refundEntity->setQtyRefunded(1);
            $refundEntity->setRowTotal($refundAmount);
            $refundEntity->setRefundAmount($refundAmount);
            $refundEntity->setStatus(Refund::STATUS_N);
            $refundEntity->setCreatedAt(new \DateTimeImmutable());

            $event->getManager()->persist($refundEntity);
        }

        $newShippingEntity->setGrandTotal(
            $newShippingEntity->getShippingAmount() + 
            $newShippingEntity->getSubtotal() +
            $newShippingEntity->getDiscountAmount()
        );
        
        $newShippingEntity->setBaseGrandTotal(
            $newShippingEntity->getBaseShippingAmount() + 
            $newShippingEntity->getBaseSubtotal() +
            $newShippingEntity->getBaseDiscountAmount()
        );

        $event->getManager()->persist($historyEntity);
    }

    /**
     * Get change detail.
     * 
     * @param  SaleOrder $oldShippingEntity
     * @param  SaleOrder $newShippingEntity
     * @return string
     */
    private function getComment(SaleOrder $oldShippingEntity, SaleOrder $newShippingEntity): string
    {
        $comment = sprintf('%s -> %s', $oldShippingEntity->getShippingDescription(), $newShippingEntity->getShippingDescription());

        return $comment;
    }
}