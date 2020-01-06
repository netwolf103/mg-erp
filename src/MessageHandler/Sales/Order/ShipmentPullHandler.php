<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Message\Sales\Order\ShipmentPull;

use App\Api\Magento1x\Soap\Sales\OrderShipmentSoap;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Shipment;
use App\Entity\Sales\Order\Shipment\Item as ShipmentItem;
use App\Entity\Sales\Order\Shipment\Track;
use App\Message\Sales\Order\Shipment\PlatformPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for order shipment pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentPullHandler extends MessageHandlerAbstract
{
    /**
     * Shipment pull handler.
     * 
     * @param  ShipmentPull $shipmentPull
     * @return void
     */
    public function __invoke(ShipmentPull $shipmentPull)
    {
        $data = $shipmentPull->getData();

        $entityManager = $this->getDoctrine()->getManager();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($data['order_id']);

        if (!$orderEntity) {
            return;
        }

        if ($orderEntity->getShipments()->isEmpty()) {
            $shipmentEntity = new Shipment;
            $shipmentEntity->setParent($orderEntity);
            $shipmentEntity->setIncrementId($data['shipment_increment_id']);
            $shipmentEntity->setCreatedAt(new \DateTimeImmutable());
        } else {
            $shipmentEntity = $orderEntity->getShipments()->last();
        }

        $shipmentEntity->setUpdatedAt(new \DateTimeImmutable());

        $shipmentTrackEntity = new Track;
        $shipmentTrackEntity->setParent($shipmentEntity);
        $shipmentTrackEntity->setCarrierCode($data['carrier_code']);
        $shipmentTrackEntity->setTitle($data['title']);
        $shipmentTrackEntity->setTrackNumber($data['track_number']);
        $shipmentTrackEntity->setCreatedAt(new \DateTimeImmutable());
        $shipmentTrackEntity->setUpdatedAt(new \DateTimeImmutable());

        $totalQty = 0;
        foreach ($orderEntity->getItems() as $orderItemEntity) {
            $_qty = $data['item:id:'.$orderItemEntity->getId()] ?? 0;
            if (!$_qty) {
                continue;
            }
            $totalQty += $_qty;

            $orderItemEntity->setQtyShipped($_qty + $orderItemEntity->getQtyShipped());
            $entityManager->persist($orderItemEntity);

            $shipmentItemEntity = new ShipmentItem;
            $shipmentItemEntity->setParent($shipmentEntity);
            $shipmentItemEntity->setSku($orderItemEntity->getSku());
            $shipmentItemEntity->setName($orderItemEntity->getName());
            $shipmentItemEntity->setWeight($orderItemEntity->getWeight());
            $shipmentItemEntity->setPrice($orderItemEntity->getPrice());
            $shipmentItemEntity->setQty($_qty);

            $entityManager->persist($shipmentItemEntity);           
        }
        $shipmentEntity->setTotalQty($totalQty);

        if ($orderEntity->getQtyNotShipped()) {
            $orderEntity->setStatus(SaleOrder::ORDER_STATUS_PART_SHIPPED);
        } else {
            $orderEntity->setStatus(SaleOrder::ORDER_STATUS_COMPLETE);
        }

        $entityManager->persist($orderEntity);
        $entityManager->persist($shipmentTrackEntity);
        $entityManager->persist($shipmentEntity);
        $entityManager->flush();

        $this->dispatchMessage(new PlatformPush($orderEntity->getId()));

        $this->success(
            sprintf("Order #%s, Shipment #%s, Pull Done", $orderEntity->getIncrementId(), $data['shipment_increment_id'])
        );                                     
    }

    /**
     * Return OrderShipmentSoap client.
     * 
     * @return OrderShipmentSoap
     */
    private function getClient(): OrderShipmentSoap
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = new OrderShipmentSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? ''); 

        return $client;
    }    
}