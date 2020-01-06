<?php

namespace App\EventListener\Sales\Order\Shipment;

use Symfony\Component\EventDispatcher\Event;

use App\Api\Magento1x\Soap\Sales\OrderShipmentSoap;
use App\Entity\Sales\Order\Shipment;

use App\Traits\ConfigTrait;

/**
 * Listener of item.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TrackListener
{
    /**
     * @param App\Event\Sales\Order\Shipment\TrackEvent|Event $event
     * @return void
     */    
    public function onSalesOrderShipmentTrackPreadd(Event $event): void
    {
        ConfigTrait::loadConfigs($event->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = null;
        if ($event->getOrder()->getShipments()->isEmpty()) {
            $client = new OrderShipmentSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? '');
            $shipmentIncrementId = $client->callSalesOrderShipmentCreate($event->getOrder()->getIncrementId(), [], null, 1);

            $shipmentEntity = new Shipment;

            $shipmentEntity->setParent($event->getOrder());
            $shipmentEntity->setIncrementId($shipmentIncrementId);
            $shipmentEntity->setTotalQty($event->getOrder()->getTotalQtyOrdered());
            $shipmentEntity->setCreatedAt(new \DateTimeImmutable());
            $shipmentEntity->setUpdatedAt(new \DateTimeImmutable());
        } else {
            $shipmentEntity = $event->getOrder()->getShipments()->first();
        }

        if (is_null($client)) {
            $client = new OrderShipmentSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? '');
        }

        $client->callSalesOrderShipmentAddTrack(
            $shipmentEntity->getIncrementId(), 
            $event->getShipmentTrackEntity()->getCarrierCode(),
            $event->getShipmentTrackEntity()->getTitle(),
            $event->getShipmentTrackEntity()->getTrackNumber()
        );

        $event->setShipmentEntity($shipmentEntity);
    }
}