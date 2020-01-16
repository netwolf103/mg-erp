<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;

use App\Entity\SaleOrder;
use App\Message\Sales\Order\ShippingmethodPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for order shipping method push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShippingmethodPushHandler extends MessageHandlerAbstract
{
    /**
     * Push order shipping method handler.
     * 
     * @param  ShippingmethodPush $shippingmethodPush
     * @return void
     */
    public function __invoke(ShippingmethodPush $shippingmethodPush)
    {
        $orderId = $shippingmethodPush->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity) {
            return;
        }

        try {
            $client = $this->getClient();
            $client->callOrderUpdate(
                $orderEntity->getIncrementId(),
                [
                    'shipping_description' => $orderEntity->getShippingDescription(),
                    'base_shipping_amount' => $orderEntity->getBaseShippingAmount(),
                    'base_shipping_invoiced' => $orderEntity->getBaseShippingAmount(),
                    'shipping_amount' => $orderEntity->getShippingAmount(),
                    'shipping_invoiced' => $orderEntity->getShippingAmount(),
                    'shipping_method' => $orderEntity->getShippingMethod(),
                ]
            );
        
            $this->success(
                sprintf("Order shipping method #%s, Push Done", $orderEntity->getIncrementId())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }          
    }

    /**
     * Return OrderSoap client.
     * 
     * @return OrderSoap
     */
    private function getClient(): OrderSoap
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = new OrderSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? ''); 

        return $client;        
    }
}