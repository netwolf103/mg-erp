<?php
namespace App\MessageHandler\Sales\Order\Payment;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Payment\TransactionSoap;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Payment\Transaction;
use App\Message\Sales\Order\Payment\TransactionPull;

use App\Traits\ConfigTrait;

/**
 * Message handler for order payment transaction pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TransactionPullHandler extends MessageHandlerAbstract
{
    /**
     * Order payment transaction pull handler.
     * 
     * @param  TransactionPull $transactionPull
     * @return void
     */
    public function __invoke(TransactionPull $transactionPull)
    {
        $orderId = $transactionPull->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity) {
            return;
        }

        try {
            $client = $this->getClient();
            $response = $client->callSalesOrderPaymentTransactionInfo($orderEntity->getIncrementId());

            foreach ($response as $_transaction) {
                $transactionEntity = $entityManager->getRepository(Transaction::class)->findOneBy([
                    'parent_order' => $orderId,
                    'txn_id' => $_transaction['txn_id']
                ]);

                if ($transactionEntity) {
                    continue;
                }                
                
                $transactionEntity = new Transaction;
                $transactionEntity->setParentOrder($orderEntity);
                $transactionEntity->setTxnId($_transaction['txn_id']);
                $transactionEntity->setParentTxnId($_transaction['parent_txn_id']);
                $transactionEntity->setTxnType($_transaction['txn_type']);
                $transactionEntity->setCreatedAt(new \DateTimeImmutable($_transaction['created_at']));

                $entityManager->persist($transactionEntity);
            }

            $entityManager->flush();

            $this->success(
                sprintf("Order #%s, transaction Pull Done", $orderEntity->getIncrementId())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }             
    }

    /**
     * Return TransactionSoap client.
     * 
     * @return TransactionSoap
     */
    private function getClient(): TransactionSoap
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = new TransactionSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? ''); 

        return $client;        
    }
}