<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;

use App\Entity\SaleOrder;
use App\Message\Sales\Order\UnHoldPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for order unhold push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class UnHoldPushHandler extends MessageHandlerAbstract
{
    /**
     * Order hold handler.
     * 
     * @param  UnHoldPush $unHoldPush
     * @return void
     */
    public function __invoke(UnHoldPush $unHoldPush)
    {
        $orderId = $unHoldPush->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity) {
            return;
        }

        try {
            $client = $this->getClient();
            $response = $client->callUnhold($orderEntity->getIncrementId());          

            $this->success(
                sprintf("Order #%s, Unhold Done", $orderEntity->getIncrementId())
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