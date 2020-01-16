<?php

namespace App\Command\Magento\Sync\Config\Shipping;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Api\Magento1x\Soap\Config\Shipping\MethodSoap;

use App\Command\Magento\SyncCommand;

use App\Entity\Config\Shipping\Method;

/**
 * Command of sync shipping method config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MethodCommand extends SyncCommand
{
    protected static $entityType = 'magento.config.shipping.method';
    protected static $defaultName = 'app:magento:sync-config-shipping-method';
    protected static $description =  'Sync shipping methods for config.';
    protected static $title =  'Get Magento Shipping Methods';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setSyncRecord();
        
        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);

        $entityManager = $this->getDoctrine()->getManager();

        $client = $this->createClient($input, MethodSoap::class);

        $io->section('Loading Shipping Method List');

        $shippingMethods = $client->callConfigShippingMethodList();
        $total = count($shippingMethods);
        $adder = 0;

        foreach ($shippingMethods as $method) {
            $io->writeln(sprintf('%s # %d/%d', $method['title'], $total, ++$adder));

            // Method Entity
            $methodEntity = new Method();
            $methodEntity->setCode($method['code']);
            $methodEntity->setActive($method['active']);
            $methodEntity->setTitle($method['title']);
            $methodEntity->setName($method['name']);
            $methodEntity->setPrice($method['price']);

            $entityManager->persist($methodEntity);
            $entityManager->flush();
        }

        $io->success('Shipping method successfully synced.');      
    }    
}