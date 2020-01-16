<?php

namespace App\Command\Magento\Sync\Sales;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Api\Magento1x\Soap\Sales\OrderSoap;

use App\Command\Magento\SyncCommand;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Payment;
use App\Entity\Sales\Order\Item;
use App\Entity\Sales\Order\Address;
use App\Entity\Sales\Order\Comment;
use App\Entity\Product;

use App\Message\Sales\Order\InvoicePull;
use App\Message\Sales\Order\Payment\TransactionPull;
use App\Message\Sales\Order\Address\GeoPull;

/**
 * Command of sync sales order.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderCommand extends SyncCommand
{
    protected static $entityType = 'magento.sales.order'; 
    protected static $defaultName = 'app:magento:sync-sales-order';
    protected static $title =  'Get Magento Sales Order';
    protected static $description =  'Sync all sales order.';
    protected static $complexFilterKey =  'order_id';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setSyncRecord();
        
        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);

        $entityManager = $this->getDoctrine()->getManager();

        $client = $this->createClient($input, OrderSoap::class);

        $io->section('Loading Order List');   

        $orders = $client->callOrderList($this->getFilter($input->getOption('filter')));
        $total = count($orders);
        $adder = 0;

        // Order List
        foreach ($orders as $order) {
            $io->writeln(sprintf('%s # %d/%d', $order->increment_id, $total, ++$adder));
            $io->section('Loading Order');

            $orderEntity = $entityManager->getRepository(SaleOrder::class)->findOneBy([
                'increment_id' => $order->increment_id,
            ]);
            if ($orderEntity) {
                $io->warning('Existed');
                continue;
            }

            $order = $client->callOrderInfo($order->increment_id);

            // Order Entity
            $orderEntity = new SaleOrder();
            $orderEntity->setIncrementId($order->increment_id);

            $orderEntity->setCreatedAt($this->convertDatetime($order->created_at));
            $orderEntity->setUpdatedAt($this->convertDatetime($order->updated_at));
            $orderEntity->setTaxAmount($order->tax_amount);
            $orderEntity->setShippingAmount($order->shipping_amount);
            $orderEntity->setDiscountAmount($order->discount_amount);
            $orderEntity->setSubtotal($order->subtotal);
            $orderEntity->setGrandTotal($order->grand_total);
            $orderEntity->setTotalQtyOrdered($order->total_qty_ordered);
            $orderEntity->setBaseTaxAmount($order->base_tax_amount);
            $orderEntity->setBaseShippingAmount($order->base_shipping_amount);
            $orderEntity->setBaseDiscountAmount($order->base_discount_amount);
            $orderEntity->setBaseSubtotal($order->base_subtotal);
            $orderEntity->setBaseGrandTotal($order->base_grand_total);
            $orderEntity->setBaseGrandTotal($order->base_grand_total);
            $orderEntity->setStoreToBaseRate($order->store_to_base_rate);
            $orderEntity->setStoreToOrderRate($order->store_to_order_rate);
            $orderEntity->setBaseToGlobalRate($order->base_to_global_rate);
            $orderEntity->setBaseToOrderRate($order->base_to_order_rate);
            $orderEntity->setWeight($order->weight);
            $orderEntity->setStoreName($order->store_name);
            $orderEntity->setRemoteIp($order->remote_ip);
            $orderEntity->setStatus($order->status ?? $order->state);
            $orderEntity->setState($order->state);
            $orderEntity->setGlobalCurrencyCode($order->global_currency_code);
            $orderEntity->setBaseCurrencyCode($order->base_currency_code);
            $orderEntity->setStoreCurrencyCode($order->store_currency_code);
            $orderEntity->setOrderCurrencyCode($order->order_currency_code);
            $orderEntity->setShippingMethod($order->shipping_method ?? '');
            $orderEntity->setShippingDescription($order->shipping_description ?? '');
            $orderEntity->setCustomerEmail($order->customer_email ?? '');
            $orderEntity->setQuoteId($order->quote_id);
            $orderEntity->setIsVirtual($order->is_virtual);
            $orderEntity->setCustomerGroupId($order->customer_group_id);
            $orderEntity->setCustomerNoteNotify($order->customer_note_notify);
            $orderEntity->setCustomerIsGuest($order->customer_is_guest);
            $orderEntity->setOrderId($order->order_id);

            // Order Address Entity
            $address = [$order->billing_address];
            if (isset($order->shipping_address)) {
                $address[] = $order->shipping_address;
            }
            foreach ($address as $addr) {
                $orderAddressEntity = new Address();
                $orderAddressEntity->setAddressType($addr->address_type ?? '');
                $orderAddressEntity->setFirstname($addr->firstname ?? '');
                $orderAddressEntity->setLastname($addr->lastname ?? '');
                $orderAddressEntity->setStreet($addr->street ?? '');
                $orderAddressEntity->setCity($addr->city ?? '');
                $orderAddressEntity->setRegion($addr->region ?? '');
                $orderAddressEntity->setPostcode($addr->postcode ?? '');
                $orderAddressEntity->setCountryId($addr->country_id ?? '');
                $orderAddressEntity->setTelephone($addr->telephone ?? '');

                $orderEntity->addAddress($orderAddressEntity);
                $entityManager->persist($orderAddressEntity);
            }

            // Order Payment Entity
            $orderPaymentEntity = new Payment();
            $orderPaymentEntity->setAmountOrdered($order->payment->amount_ordered);
            $orderPaymentEntity->setShippingAmount($order->payment->shipping_amount);
            $orderPaymentEntity->setBaseAmountOrdered($order->payment->base_amount_ordered);
            $orderPaymentEntity->setBaseShippingAmount($order->payment->base_shipping_amount);
            $orderPaymentEntity->setMethod($order->payment->method);
            $orderPaymentEntity->setPaymentId($order->payment->payment_id);

            $orderEntity->setPayment($orderPaymentEntity);
            $entityManager->persist($orderPaymentEntity);

            foreach ($order->items as $item) {
                // Product Entity
                $productEntity = $entityManager->getRepository(Product::class)->findOneBy([
                    'sku' => $item->sku,
                ]);

                if (!$productEntity) {
                    continue;
                }

                // Order Item Entity
                $orderItemEntity = new Item();

                $orderItemEntity->setProduct($productEntity);
                $orderItemEntity->setQuoteItemId($item->quote_item_id);
                $orderItemEntity->setCreatedAt($this->convertDatetime($item->created_at));
                $orderItemEntity->setUpdatedAt($this->convertDatetime($item->updated_at));
                $orderItemEntity->setProductType($item->product_type);
                $orderItemEntity->setProductOptions($item->product_options);
                $orderItemEntity->setWeight($item->weight ?? 0);
                $orderItemEntity->setIsVirtual($item->is_virtual ?? 0);
                $orderItemEntity->setSku($item->sku);
                $orderItemEntity->setName($item->name);
                $orderItemEntity->setFreeShipping($item->free_shipping);
                $orderItemEntity->setIsQtyDecimal($item->is_qty_decimal);
                $orderItemEntity->setNoDiscount($item->no_discount);
                $orderItemEntity->setQtyCanceled($item->qty_canceled);
                $orderItemEntity->setQtyInvoiced($item->qty_invoiced);
                $orderItemEntity->setQtyOrdered($item->qty_ordered);
                $orderItemEntity->setQtyRefunded($item->qty_refunded);
                $orderItemEntity->setQtyShipped($item->qty_shipped);
                $orderItemEntity->setPrice($item->price);
                $orderItemEntity->setBasePrice($item->base_price);
                $orderItemEntity->setOriginalPrice($item->original_price);
                $orderItemEntity->setTaxPercent($item->tax_percent);
                $orderItemEntity->setTaxAmount($item->tax_amount);
                $orderItemEntity->setBaseTaxAmount($item->base_tax_amount);
                $orderItemEntity->setTaxInvoiced($item->tax_invoiced);
                $orderItemEntity->setBaseTaxInvoiced($item->base_tax_invoiced);
                $orderItemEntity->setDiscountPercent($item->discount_percent);
                $orderItemEntity->setDiscountAmount($item->discount_amount);
                $orderItemEntity->setBaseDiscountAmount($item->base_discount_amount);
                $orderItemEntity->setDiscountInvoiced($item->discount_invoiced);
                $orderItemEntity->setBaseDiscountInvoiced($item->base_discount_invoiced);
                $orderItemEntity->setAmountRefunded($item->amount_refunded);
                $orderItemEntity->setBaseAmountRefunded($item->base_amount_refunded);
                $orderItemEntity->setRowTotal($item->row_total);
                $orderItemEntity->setBaseRowTotal($item->base_row_total);
                $orderItemEntity->setRowInvoiced($item->row_invoiced);
                $orderItemEntity->setBaseRowInvoiced($item->base_row_invoiced);
                $orderItemEntity->setRowWeight($item->row_weight);
                $orderItemEntity->setWeeeTaxApplied($item->weee_tax_applied);
                $orderItemEntity->setWeeeTaxAppliedAmount($item->weee_tax_applied_amount);
                $orderItemEntity->setWeeeTaxAppliedRowAmount($item->weee_tax_applied_row_amount);
                $orderItemEntity->setBaseWeeeTaxAppliedAmount($item->base_weee_tax_applied_amount);
                $orderItemEntity->setWeeeTaxDisposition($item->weee_tax_disposition);
                $orderItemEntity->setWeeeTaxRowDisposition($item->weee_tax_row_disposition);
                $orderItemEntity->setBaseWeeeTaxDisposition($item->base_weee_tax_disposition);
                $orderItemEntity->setBaseWeeeTaxRowDisposition($item->base_weee_tax_row_disposition);

                $orderEntity->addItem($orderItemEntity);
                $entityManager->persist($orderItemEntity);
            }

            foreach ($order->status_history as $status_history) {
                // Order Comment Entity
                $orderCommentEntity = new Comment();

                if (isset($status_history->is_customer_notified)) {
                    $orderCommentEntity->setIsCustomerNotified($status_history->is_customer_notified);
                }
                $orderCommentEntity->setComment($status_history->comment ?? '');
                $orderCommentEntity->setStatus($status_history->status ?? '');
                $orderCommentEntity->setEntityName($status_history->entity_name ?? '');
                $orderCommentEntity->setCreatedAt($this->convertDatetime($status_history->created_at));

                $orderEntity->addComment($orderCommentEntity);
                $entityManager->persist($orderCommentEntity);                
            }

            // Set Order Type
            $item_types = [];
            foreach ($orderEntity->getItems() as $_item) {
                $item_types[] = $_item->getItemType();
            }
            $item_types = array_unique($item_types);
            if (count($item_types) == 1) {
                $order_type = reset($item_types);
            } else {
                $order_type = SaleOrder::ORDER_TYPE_COMBINATION;
            }
            $orderEntity->setOrderType($order_type);

            $this->getSyncRecord()->setUpdatedAt(new \DateTimeImmutable());
            $this->getSyncRecord()->setLastEntityId($order->order_id);

            $entityManager->persist($orderEntity);
            $entityManager->persist($this->getSyncRecord());
            $entityManager->flush();

            $this->dispatchMessage(new InvoicePull($orderEntity->getId()));
            $this->dispatchMessage(new TransactionPull($orderEntity->getId()));
            if ($orderEntity->getShippingAddress()) {
                $this->dispatchMessage(new GeoPull($orderEntity->getShippingAddress()->getId()));
            }     
        }

        $io->success('Orders successfully synced.');
    }
}
