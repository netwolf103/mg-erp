<?php
namespace App\MessageHandler\Sales;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;

use App\Entity\SaleOrder;
use App\Message\Sales\OrderPull;

use App\Traits\ConfigTrait;

/**
 * Message handler for sale order pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderPullHandler extends MessageHandlerAbstract
{
    /**
     * Order handler.
     * 
     * @param  OrderPull $orderPull
     * @return void
     */
    public function __invoke(OrderPull $orderPull)
    {
        $orderId = $orderPull->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity) {
            return;
        }

        $client = $this->getClient();
        $order = $client->callOrderInfo($orderEntity->getIncrementId());
       
        $orderEntity->setStatus($order->status);
        $orderEntity->setState($order->state);

        $entityManager->persist($orderEntity);
        $entityManager->flush();

        $this->success(
            sprintf("Order #%s, Done", $orderEntity->getIncrementId())
        );               
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