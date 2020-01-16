<?php
namespace App\MessageHandler\Sales\Order\Email;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;
use App\Entity\Sales\Order\ConfirmEmailHistory;
use App\Message\Sales\Order\Email\ConfirmPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for send order confirm email push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ConfirmPushHandler extends MessageHandlerAbstract
{
    /**
     * Order confirm email handler.
     * 
     * @param  ConfirmPush $emailPush
     * @return void
     */
    public function __invoke(ConfirmPush $confirmPush)
    {
        $confirmId = $confirmPush->getConfirmId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $confirmEmailHistoryEntity = $entityManager->getRepository(ConfirmEmailHistory::class)->find($confirmId);

        $orderEntity = $confirmEmailHistoryEntity->getParent();

        if (!$confirmEmailHistoryEntity || !$orderEntity) {
            return;
        }

        try {
            $client = $this->getClient();

            $response = $client->callSendConfirmEmail($orderEntity->getIncrementId());

            if (!$response) {
                $confirmEmailHistoryEntity->setStatus(ConfirmEmailHistory::STATUS_FAILURE);
                throw new Exception("Order #%s confirm email, Push Failure", $orderEntity->getIncrementId());
            }

            $confirmEmailHistoryEntity->setStatus(ConfirmEmailHistory::STATUS_SUCCESS);

            $this->success(
                sprintf("Order #%s confirm email, Push Done", $orderEntity->getIncrementId())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }

        $entityManager->flush();      
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