<?php
namespace App\MessageHandler\Sales\Order\Shipment;

use App\MessageHandler\MessageHandlerAbstract;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Payment\Transaction;
use App\Message\Sales\Order\Shipment\PlatformPush;

/**
 * Message handler for platform shipment push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PlatformPushHandler extends MessageHandlerAbstract
{
    protected static $_paymentTrackApis;
    protected static $_carrier;

    /**
     * Shipment for Platform handler.
     * 
     * @param  PlatformPush $platformPush
     * @return void
     */
    public function __invoke(PlatformPush $platformPush)
    {
        $orderId = $platformPush->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity || $orderEntity->getPaymentTransactions()->isEmpty()) {
            return;
        }

        $transactions = $orderEntity->getPaymentTransactions()->filter(function(Transaction $transaction) {
            return $transaction->getTxnType() == Transaction::TYPE_CAPTURE;
        });

        $transaction = $transactions->first();

        try {
            $client = $this->getClient($orderEntity);

            if (!$client) {
                throw new \Exception(sprintf('%s -> No api class found.', $orderEntity->getPayment()->getMethod()));
            }

            $success = false;

            foreach ($orderEntity->getShipments() as $shipment) {
                foreach ($shipment->getTracks() as $track) {

                    if ($client instanceof \App\Api\Oceanpayment\Tracking) {
                        $carrier = $this->getCarrier($track->getTitle());
                        $tracking_site = $carrier['site'] ?? 'N/A';

                        $success = $client->add([
                            'order_number' => $orderEntity->getIncrementId(),
                            'tracking_number' => trim($track->getTrackNumber()),
                            'tracking_site' => $tracking_site,
                        ]);
                        
                    } elseif ($client instanceof \App\Api\Paypal\Reset\Tracking) {
                        $carrier = $this->getCarrier($track->getTitle());
                        $carrier_code = $carrier['carrier'] ?? 'CN_OTHER';

                        $response = $client->add([
                            'transaction_id' => $transaction->getTxnId(),
                            'tracking_number' => $track->getTrackNumber(),
                            'status' => 'SHIPPED',
                            'carrier' => strtoupper($carrier_code),
                            'carrier_name_other' => $track->getTitle()
                        ]);

                        if (!isset($response->errors) || count($response->errors) == 0) {
                            $success = true;
                        }  
                    }                                     
                }
            }

            if (!$success) {
                throw new \Exception(sprintf('%s -> Push failed.', $orderEntity->getPayment()->getMethod()));
            }

            $orderEntity->setTrackingNumberToPlatformSynced(true);
            $entityManager->persist($orderEntity);
            $entityManager->flush();

            $this->success(
                sprintf("Order #%s, tracking number Push Done", $orderEntity->getIncrementId())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Return Tracking client.
     * 
     * @return Tracking
     */
    protected function getClient(SaleOrder $order)
    {

        $apiClass = $this->getPaymentTrackApiClass($order->getPayment()->getMethod());

        if (!$apiClass) {
            return false;
        }

        $apiParams = $this->getParameter()->get($apiClass);

        $client = new $apiClass($apiParams); 

        return $client;
    }

    /**
     * Return api class.
     * 
     * @param  string $method [description]
     * @return string
     */
    protected function getPaymentTrackApiClass(string $method): ?string
    {
        if (is_null(static::$_paymentTrackApis)) {
            $paymentMethods = $this->getParameter()->get('payment_methods');

            static::$_paymentTrackApis = $paymentMethods['tracking_apis'] ?? [];
        }
        
        return static::$_paymentTrackApis[$method] ?? false;
    }

    /**
     * Get tracking carrier data.
     * 
     * @param  string $carrier
     * @return array
     */
    protected function getCarrier(string $carrier): array
    {
        if (is_null(static::$_carrier)) {
            static::$_carrier = $this->getParameter()->get('tracking');
        }

        $carrier = strtolower($carrier);
        
        return static::$_carrier[$carrier] ?? [];
    }    
}