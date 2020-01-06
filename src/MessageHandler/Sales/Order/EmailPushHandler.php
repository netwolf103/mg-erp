<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;

use App\Entity\SaleOrder;
use App\Message\Sales\Order\EmailPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for order email push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class EmailPushHandler extends MessageHandlerAbstract
{
    /**
     * Order email handler.
     * 
     * @param  EmailPush $emailPush
     * @return void
     */
    public function __invoke(EmailPush $emailPush)
    {
        $orderId = $emailPush->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity) {
            return;
        }

        try {
            $client = $this->getClient();

            $response = $client->callOrderUpdate(
                $orderEntity->getIncrementId(),
                [
                    'customer_email' => $orderEntity->getCustomerEmail(),
                ]
            );

            $this->success(
                sprintf("Order #%s customer email, Push Done", $orderEntity->getIncrementId())
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