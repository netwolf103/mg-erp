<?php

namespace App\Command\Magento\Sync\Sales\Order;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Api\Magento1x\Soap\Sales\OrderSoap;
use App\Api\Magento1x\Soap\Sales\OrderShipmentSoap;

use App\Command\Magento\SyncCommand;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Shipment;
use App\Entity\Sales\Order\Shipment\Item;
use App\Entity\Sales\Order\Shipment\Track;

/**
 * Command for sync sales order shipment
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentCommand extends SyncCommand
{
    protected static $entityType = 'magento.sales.order.shipment';
    protected static $defaultName = 'app:magento:sync-sales-order-shipment';
    protected static $title =  'Get Magento Sales Order Shipments';
    protected static $description =  'Sync all sales order shipment.';
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
            if (!$orderEntity) {
                $io->warning('Order not found.');
                continue;
            }

            $io->section('Loading Shipment');

            $clientShipment = $client->copySession(new OrderShipmentSoap());
            $shipments = $clientShipment->callSalesOrderShipmentList([
                'complex_filter' => [
                    [
                        'key' => 'order_id',
                        'value' => ['key' => 'eq', 'value' => $order->order_id]
                    ]
                ]
            ]);

            // Shipment List
            foreach ($shipments as $shipment) {
                $shipment = $clientShipment->callSalesOrderShipmentInfo($shipment->increment_id);

                // Shipment Entity
                $shipmentEntity = new Shipment;
                $shipmentEntity->setIncrementId($shipment->increment_id);
                $shipmentEntity->setStoreId($shipment->store_id);
                $shipmentEntity->setTotalQty($shipment->total_qty);
                $shipmentEntity->setCreatedAt($this->convertDatetime($shipment->created_at));
                $shipmentEntity->setUpdatedAt($this->convertDatetime($shipment->updated_at));

                foreach ($shipment->items as $_item) {
                    // Shipment Item Entity
                    $shipmentItemEntity = new Item;
                    $shipmentItemEntity->setSku($_item->sku);
                    $shipmentItemEntity->setName($_item->name);
                    $shipmentItemEntity->setWeight($_item->weight);
                    $shipmentItemEntity->setPrice($_item->price);
                    $shipmentItemEntity->setQty($_item->qty);

                    $shipmentEntity->addItem($shipmentItemEntity);
                    $entityManager->persist($shipmentItemEntity);
                }

                foreach ($shipment->tracks as $track) {
                    // Shipment Track Entity
                    $shipmentTrackEntity = new Track;
                    $shipmentTrackEntity->setCarrierCode($track->carrier_code);
                    $shipmentTrackEntity->setTitle($track->title);
                    $shipmentTrackEntity->setTrackNumber($track->number);
                    $shipmentTrackEntity->setCreatedAt($this->convertDatetime($track->created_at));
                    $shipmentTrackEntity->setUpdatedAt($this->convertDatetime($track->updated_at));

                    $shipmentEntity->addTrack($shipmentTrackEntity);
                    $entityManager->persist($shipmentTrackEntity);                    
                }

                $orderEntity->addShipment($shipmentEntity);

                $entityManager->persist($shipmentEntity);
            }

            $this->getSyncRecord()->setUpdatedAt(new \DateTimeImmutable());
            $this->getSyncRecord()->setLastEntityId($order->order_id);            

            $entityManager->persist($orderEntity);
            $entityManager->persist($this->getSyncRecord());
            $entityManager->flush();                       
        }      

        $io->success('Shipments successfully synced.');
    }
}
