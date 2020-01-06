<?php

namespace App\Command\Magento\Sync;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Api\Magento1x\Soap\Customer\CustomerSoap;
use App\Api\Magento1x\Soap\Customer\AddressSoap;

use App\Command\Magento\SyncCommand;

use App\Entity\Customer;
use App\Entity\Customer\Address;

/**
 * Command for customer
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CustomerCommand extends SyncCommand
{
    protected static $entityType = 'magento.customer';
    protected static $defaultName = 'app:magento:sync-customer';
    protected static $title =  'Get Magento Customers';
    protected static $description =  'Sync all customers.';
    protected static $complexFilterKey =  'customer_id';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->setSyncRecord();
        
        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);

        $entityManager = $this->getDoctrine()->getManager();

        $client = $this->createClient($input, CustomerSoap::class);

        $io->section('Loading Customer List');
       
        $customers = $client->callCustomerCustomerList($this->getFilter($input->getOption('filter')));
        $total = count($customers);
        $adder = 0;

        foreach ($customers as $customer) {
            $io->writeln(sprintf('%s # %d/%d', $customer->email, $total, ++$adder));
            $io->section('Loading Customer');

            $customer = $client->callCustomerCustomerInfo($customer->customer_id);

            // Customer Entity
            $customerEntity = new Customer();
            $customerEntity->setEmail($customer->email);
            $customerEntity->setFirstname($customer->firstname);
            $customerEntity->setLastname($customer->lastname);
            $customerEntity->setCreatedAt($this->convertDatetime($customer->created_at));
            $customerEntity->setUpdatedAt($this->convertDatetime($customer->updated_at));

            $io->section('Loading Customer Address');
            $clientAddress = $client->copySession(new AddressSoap());
            $addresses = $clientAddress->callCustomerAddressList($customer->customer_id);
            foreach ($addresses as $address) {
                // Customer Address Entity
                $address = $clientAddress->callCustomerAddressInfo($address->customer_address_id);
                
                $customerAddressEntity = new Address();
                $customerAddressEntity->setCity($address->city);
                $customerAddressEntity->setCountryId($address->country_id);
                $customerAddressEntity->setFirstname($address->firstname);
                $customerAddressEntity->setLastname($address->lastname);
                $customerAddressEntity->setPostcode($address->postcode);
                $customerAddressEntity->setPrefix($address->prefix);
                $customerAddressEntity->setRegion($address->region);
                $customerAddressEntity->setStreet($address->street);
                $customerAddressEntity->setTelephone($address->telephone);
                $customerAddressEntity->setIsDefaultBilling($address->is_default_billing);
                $customerAddressEntity->setIsDefaultShipping($address->is_default_shipping);
                $customerAddressEntity->setCreatedAt($this->convertDatetime($address->created_at));
                $customerAddressEntity->setUpdatedAt($this->convertDatetime($address->updated_at));                

                $customerEntity->addAddress($customerAddressEntity);

                $entityManager->persist($customerAddressEntity);
            }

            $this->getSyncRecord()->setUpdatedAt(new \DateTimeImmutable());
            $this->getSyncRecord()->setLastEntityId($customer->customer_id);

            $entityManager->persist($customerEntity);
            $entityManager->persist($this->getSyncRecord());
            $entityManager->flush();
        }

        $io->success('Customer successfully synced.');
    }
}
