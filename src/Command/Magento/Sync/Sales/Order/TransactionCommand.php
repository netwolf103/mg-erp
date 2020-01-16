<?php

namespace App\Command\Magento\Sync\Sales\Order;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Command\Magento\SyncCommand;

use App\Entity\SaleOrder;
use App\Message\Sales\Order\Payment\TransactionPull;

/**
 * Command of sync order transaction.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TransactionCommand extends SyncCommand
{
    protected static $entityType = 'magento.sales.order.payment.transaction';
    protected static $defaultName = 'app:magento:sync-sales-order-payment-transaction';
    protected static $title =  'Pull Magento Sales Order Payment Transactions';
    protected static $description =  'Sync all sales order payment transactions.';
    protected static $complexFilterKey =  'order_id';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setSyncRecord();
        
        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);
        
        $ordersEntity = $this->getDoctrine()->getRepository(SaleOrder::class)->findTransactionIsNull();
        foreach ($ordersEntity as $orderEntity) {
            $io->writeln(sprintf('%s #', $orderEntity->getIncrementId()));
            
            $this->dispatchMessage(new TransactionPull($orderEntity->getId()));
        }

        $io->success('Payment transactions successfully synced.');
    }
}
