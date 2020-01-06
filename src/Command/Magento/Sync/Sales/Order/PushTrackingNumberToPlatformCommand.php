<?php

namespace App\Command\Magento\Sync\Sales\Order;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Command\Magento\SyncCommand;

use App\Entity\SaleOrder;
use App\Message\Sales\Order\Shipment\PlatformPush;

/**
 * Command for push tracking number to platform.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PushTrackingNumberToPlatformCommand extends SyncCommand
{
    protected static $entityType = 'magento.sales.order.tracking.number.push.platform';
    protected static $defaultName = 'app:magento:push-tracking-number-to-platform';
    protected static $title =  'Push Tracking Number To Platform';
    protected static $description =  'Push Tracking Number To Platform.';
    protected static $complexFilterKey =  'order_id';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setSyncRecord();
        
        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);
        
        $orders = $this->getDoctrine()->getRepository(SaleOrder::class)->findPlatformNoSyncedTrackingNumber();

        foreach ($orders as $order) {
            $io->writeln(sprintf('%s #', $order->getIncrementId()));
            
            $this->dispatchMessage(new PlatformPush($order->getId()));
        }

        $io->success('Tracking number successfully joined queue.');
    }
}
