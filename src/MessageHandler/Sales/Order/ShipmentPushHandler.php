<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Message\Sales\Order\ShipmentPush;
use App\Message\Sales\Order\ShipmentPull;

use App\Api\Magento1x\Soap\Sales\OrderShipmentSoap;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Item;
use App\Entity\Sales\Order\Shipment;
use App\Entity\Sales\Order\Shipment\Item as ShipmentItem;
use App\Entity\Sales\Order\Shipment\Track;

use App\Traits\ConfigTrait;

/**
 * Message handler for order shipment push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentPushHandler extends MessageHandlerAbstract
{
    /**
     * Shipment push handler.
     * 
     * @param  ShipmentPush $shipmentPush
     * @return void
     */
    public function __invoke(ShipmentPush $shipmentPush)
    {
        $trackData = $shipmentPush->getTrack();

        $entityManager = $this->getDoctrine()->getManager();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($trackData['order_id']);

        if (!$orderEntity) {
            return;
        }

        try {
            $client = $this->getClient();

            if ($this->shipmentIsEmpty($orderEntity)) {
                $shipmentIncrementId    = $client->callSalesOrderShipmentCreate($orderEntity->getIncrementId());
            } else {
                $shipmentEntity         = $orderEntity->getShipments()->last();

                if (!$this->filterTrack($shipmentEntity, $trackData['title'], $trackData['track_number'])->isEmpty()) {
                    throw new \Exception(sprintf('%s, %s existed', $trackData['title'], $trackData['track_number']));
                }

                $shipmentIncrementId    = $shipmentEntity->getIncrementId();
            }

            $client->callSalesOrderShipmentAddTrack(
                $shipmentIncrementId, 
                $trackData['carrier_code'],
                $trackData['title'],
                $trackData['track_number']
            );

            // Send shipment email.
            $client->callSalesOrderShipmentSendInfo( $shipmentIncrementId);

            $trackData['shipment_increment_id'] = $shipmentIncrementId;             

            $this->dispatchMessage(new ShipmentPull($trackData));          

            $this->success(
                sprintf("Order #%s, Shipment #%s, Push Done", $orderEntity->getIncrementId(), $shipmentIncrementId)
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Filter tracks by track title and track number.
     * 
     * @param  Shipment $shipment
     * @param  string   $title
     * @param  string   $number
     * @return ArrayCollection
     */
    private function filterTrack(Shipment $shipment, string $title, string $number)
    {
        $tracks = $shipment->getTracks()->filter(function (Track $track) use ($title, $number) {
            return ($track->getTitle() == $title) && ($track->getTrackNumber() == $number);
        });

        return $tracks;
    }

    /**
     * Is empty shipment for order.
     * 
     * @param  SaleOrder $order
     * @return boolean
     */
    private function shipmentIsEmpty(SaleOrder $order): bool
    {
        return $order->getShipments()->isEmpty();
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