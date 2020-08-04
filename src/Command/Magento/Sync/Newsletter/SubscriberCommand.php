<?php

namespace App\Command\Magento\Sync\Newsletter;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Api\Magento1x\Soap\Newsletter\SubscriberSoap;

use App\Command\Magento\SyncCommand;

use App\Entity\Newsletter\Subscriber;

/**
 * Command of sync newsletter subscribers.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SubscriberCommand extends SyncCommand
{
    protected static $entityType = 'magento.newsletter.subscriber';
    protected static $defaultName = 'app:magento:sync-newsletter-subscriber';
    protected static $title =  'Get Magento Newsletter Subscriber';
    protected static $description =  'Sync all subscribers.';
    protected static $complexFilterKey =  'subscriber_id';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setSyncRecord();

        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);

        $entityManager = $this->getDoctrine()->getManager();

        $client = $this->createClient($input, SubscriberSoap::class);

        $io->section('Loading Subscriber List');

        $subscribers = $client->callList($this->getFilter($input->getOption('filter')));
        $total = count($subscribers);
        $adder = 0;        

        foreach ($subscribers as $subscriber) {
            $io->writeln(sprintf('%s # %d/%d', $subscriber->subscriber_email, $total, ++$adder));

            // Customer Entity
            $subscriberEntity = new Subscriber();
            $subscriberEntity->setSubscriberId($subscriber->subscriber_id);
            $subscriberEntity->setSubscriberEmail($subscriber->subscriber_email);
            $subscriberEntity->setType($subscriber->type);
            $subscriberEntity->setCustomerFirstname($subscriber->customer_firstname ?? null);
            $subscriberEntity->setCustomerMiddlename($subscriber->customer_middlename ?? null);
            $subscriberEntity->setCustomerLastname($subscriber->customer_lastname ?? null);
            $subscriberEntity->setSubscriberStatus($subscriber->subscriber_status);

            $this->getSyncRecord()->setUpdatedAt(new \DateTimeImmutable());
            $this->getSyncRecord()->setLastEntityId($subscriber->subscriber_id);

            $entityManager->persist($subscriberEntity);
            $entityManager->persist($this->getSyncRecord());
            $entityManager->flush();            
        }

        $io->success('Subscribers successfully synced.');
    }
}
