<?php

namespace App\EventListener\Sales\Order;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Sales\Order\Address;
use App\Entity\Sales\Order\Address\History;

/**
 * Event listener class of order address.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressListener
{
    /**
     * @param App\Event\Sales\Order\AddressEvent|Event $event
     * @return void
     */
    public function onSalesOrderAddressUpdate(Event $event): void
    {
        $oldAddress = $event->getOldAddress();
        $newAddress = $event->getNewAddress();

        $historyEntity = new History;
        $historyEntity->setParent($newAddress);
        $historyEntity->setAddressType($oldAddress->getAddressType());
        $historyEntity->setFirstname($oldAddress->getFirstname());
        $historyEntity->setLastname($oldAddress->getLastname());
        $historyEntity->setStreet($oldAddress->getStreet());
        $historyEntity->setCity($oldAddress->getCity());
        $historyEntity->setRegion($oldAddress->getRegion());
        $historyEntity->setPostcode($oldAddress->getPostcode());
        $historyEntity->setCountryId($oldAddress->getCountryId());
        $historyEntity->setTelephone($oldAddress->getTelephone());
        $historyEntity->setComment($this->getComment($oldAddress, $newAddress));
        $historyEntity->setCreatedAt(new \DateTimeImmutable());
        
        $event->getManager()->persist($historyEntity);
    }

    /**
     * Get change detail.
     * 
     * @param  Address $oldAddress
     * @param  Address $newAddress
     * @return string
     */
    private function getComment(Address $oldAddress, Address $newAddress): string
    {
        $comment = sprintf(
            '%s: %s, %s, %s, %s, %s, %s, %s, %s -> %s: %s, %s, %s, %s, %s, %s, %s, %s',
            $oldAddress->getAddressType(),
            $oldAddress->getFirstname(),
            $oldAddress->getLastname(),
            $oldAddress->getStreet(),
            $oldAddress->getCity(),
            $oldAddress->getRegion(),
            $oldAddress->getPostcode(),
            $oldAddress->getCountryId(),
            $oldAddress->getTelephone(),

            $newAddress->getAddressType(),
            $newAddress->getFirstname(),
            $newAddress->getLastname(),
            $newAddress->getStreet(),
            $newAddress->getCity(),
            $newAddress->getRegion(),
            $newAddress->getPostcode(),
            $newAddress->getCountryId(),
            $newAddress->getTelephone()         
        );

        return $comment;
    }
}