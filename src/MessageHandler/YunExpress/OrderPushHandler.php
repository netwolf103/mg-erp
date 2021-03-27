<?php
namespace App\MessageHandler\YunExpress;

use App\MessageHandler\MessageHandlerAbstract;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Comment;

use App\Api\Yunexpress\WayBill;
use App\Message\YunExpress\OrderPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for sale order pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderPushHandler extends MessageHandlerAbstract
{
    /**
     * Order handler.
     * 
     * @param  OrderPush $orderPush
     * @return void
     */
    public function __invoke(OrderPush $orderPush)
    {
        $orderId = $orderPush->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity) {
            return;
        }

        $shipAddress = $orderEntity->getShippingAddress();

        $client = $this->getClient();

        //$response = $client->deleteOrder(2, $orderEntity->getIncrementId());

        try {
            $response = $client->createOrder([
                [
                    'CustomerOrderNumber' => $orderEntity->getIncrementId(),
                    'ShippingMethodCode' => $client->getShippingCodeByCountryId($shipAddress->getCountryId()),
                    'PackageCount' => 1,
                    'Weight' => 0.21,
                    'Receiver' => [
                        'CountryCode' => $shipAddress->getCountryId(),
                        'FirstName' => $shipAddress->getFirstname(),
                        'LastName' => $shipAddress->getLastname(),
                        'Street' => $shipAddress->getStreet(),
                        'City' => $shipAddress->getCity(),
                        'State' => $shipAddress->getRegion(),
                        'Zip' => $shipAddress->getPostcode(),
                        'Phone' => $shipAddress->getTelephone(),
                    ],
                    'Parcels' => [
                        [
                            'EName' => 'Metal ring',
                            'CName' => '戒指',
                            'Quantity' => 1,
                            'UnitPrice' => 13,
                            'UnitWeight' => 0.21,
                            'CurrencyCode' => 'USD',
                        ]
                    ]
                ]
            ]);

            print_r($response);
        } catch (\Exception $e) {
            $comment = new Comment;
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setParent($orderEntity);
            $comment->setIsCustomerNotified(1);
            $comment->setStatus($orderEntity->getStatus());
            $comment->setComment($e->getMessage());
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        $this->success(
            sprintf("Order #%s, Done", $orderEntity->getIncrementId())
        );               
    }

    /**
     * Return YunExpress client.
     * 
     * @return OrderSoap
     */
    private function getClient(): WayBill
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configYunExpress();

        $client = new WayBill([
            'apiId' => $apiParams['account'],
            'apiSecret' => $apiParams['secret'],
            'sandbox' => !$apiParams['sandbox'],
        ]);

        return $client;
    }
}
