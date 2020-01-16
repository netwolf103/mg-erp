<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderSoap;
use App\Api\Magento1x\Soap\Sales\OrderInvoiceSoap;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Invoice;
use App\Entity\Sales\Order\Invoice\Item;
use App\Message\Sales\Order\InvoicePull;

use App\Traits\ConfigTrait;

/**
 * Message handler for order invoice pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class InvoicePullHandler extends MessageHandlerAbstract
{
    /**
     * Order invoice handler.
     * 
     * @param  InvoicePull $invoicePull
     * @return void
     */
    public function __invoke(InvoicePull $invoicePull)
    {
        $orderId = $invoicePull->getOrderId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $orderEntity = $entityManager->getRepository(SaleOrder::class)->find($orderId);

        if (!$orderEntity || $orderEntity->getInvoice()) {
            return;
        }

        $filter = [
            'complex_filter' => [
                [
                    'key' => 'increment_id',
                    'value' => [
                        'key' => 'eq',
                        'value' => $orderEntity->getIncrementId()
                    ]
                ]
            ]
        ];

        try {
            $client = $this->getClient();
            $response = $client->callOrderList($filter);
            foreach ($response as $order) {
                $clientInvoice = $client->copySession(new OrderInvoiceSoap());
                $invoices = $clientInvoice->callSalesOrderInvoiceList([
                    'complex_filter' => [
                        [
                            'key' => 'order_id',
                            'value' => ['key' => 'eq', 'value' => $order->order_id]
                        ]
                    ]
                ]);
                foreach ($invoices as $invoice) {
                     $invoice = $clientInvoice->callSalesOrderInvoiceInfo($invoice->increment_id);

                     $invoiceEntity = new Invoice;
                     $invoiceEntity->setParent($orderEntity);
                     $invoiceEntity->setIncrementId($invoice->increment_id);
                     $invoiceEntity->setStoreId($invoice->store_id);
                     $invoiceEntity->setGlobalCurrencyCode($invoice->global_currency_code);
                     $invoiceEntity->setBaseCurrencyCode($invoice->base_currency_code);
                     $invoiceEntity->setStoreCurrencyCode($invoice->store_currency_code);
                     $invoiceEntity->setOrderCurrencyCode($invoice->order_currency_code);
                     $invoiceEntity->setStoreToBaseRate($invoice->store_to_base_rate);
                     $invoiceEntity->setStoreToOrderRate($invoice->store_to_order_rate);
                     $invoiceEntity->setBaseToGlobalRate($invoice->base_to_global_rate);
                     $invoiceEntity->setBaseToOrderRate($invoice->base_to_order_rate);
                     $invoiceEntity->setSubtotal($invoice->subtotal);
                     $invoiceEntity->setBaseSubtotal($invoice->base_subtotal);
                     $invoiceEntity->setBaseGrandTotal($invoice->base_grand_total);
                     $invoiceEntity->setDiscountAmount($invoice->discount_amount);
                     $invoiceEntity->setBaseDiscountAmount($invoice->base_discount_amount);
                     $invoiceEntity->setShippingAmount($invoice->shipping_amount);
                     $invoiceEntity->setBaseShippingAmount($invoice->base_shipping_amount);
                     $invoiceEntity->setTaxAmount($invoice->tax_amount);
                     $invoiceEntity->setBaseTaxAmount($invoice->base_tax_amount);
                     $invoiceEntity->setState($invoice->state);
                     $invoiceEntity->setGrandTotal($invoice->grand_total);
                     $invoiceEntity->setCreatedAt($this->convertDatetime($invoice->created_at));
                     $invoiceEntity->setUpdatedAt($this->convertDatetime($invoice->updated_at));

                     foreach ($invoice->items as $item) {
                         $itemEntity = new Item;
                         $itemEntity->setParent($invoiceEntity);
                         $itemEntity->setWeeeTaxApplied($item->weee_tax_applied);
                         $itemEntity->setQty($item->qty);
                         $itemEntity->setPrice($item->price);
                         $itemEntity->setRowTotal($item->row_total);
                         $itemEntity->setBasePrice($item->base_price);
                         $itemEntity->setBaseRowTotal($item->base_row_total);
                         $itemEntity->setBaseWeeeTaxAppliedAmount($item->base_weee_tax_applied_amount);
                         $itemEntity->setBaseWeeeTaxAppliedRowAmount($item->base_weee_tax_applied_row_amount);
                         $itemEntity->setWeeeTaxAppliedAmount($item->weee_tax_applied_amount);
                         $itemEntity->setWeeeTaxAppliedRowAmount($item->weee_tax_applied_row_amount);
                         $itemEntity->setWeeeTaxDisposition($item->weee_tax_disposition);
                         $itemEntity->setWeeeTaxRowDisposition($item->weee_tax_row_disposition);
                         $itemEntity->setBaseWeeeTaxDisposition($item->base_weee_tax_disposition);
                         $itemEntity->setBaseWeeeTaxRowDisposition($item->base_weee_tax_row_disposition);
                         $itemEntity->setSku($item->sku);
                         $itemEntity->setName($item->name);

                         $entityManager->persist($itemEntity); 
                     }

                     $entityManager->persist($invoiceEntity);
                }
            }
            
            $entityManager->flush();
        
            $this->success(
                sprintf("Order Invoice #%s, Pull Done", $orderEntity->getIncrementId())
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