<?php
namespace App\MessageHandler;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Customer\CustomerSoap;
use App\Api\Magento1x\Soap\Customer\AddressSoap;

use App\Entity\Customer;
use App\Entity\Customer\Address;
use App\Message\CustomerPull;

use App\Traits\ConfigTrait;

/**
 * Message handler for customer pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CustomerPullHandler extends MessageHandlerAbstract
{
    /**
     * Customer pull handler.
     * 
     * @param  CustomerPull $unHoldPush
     * @return void
     */
    public function __invoke(CustomerPull $customerPull)
    {
        $userId = $customerPull->getUserId();

        $entityManager = $this->getDoctrine()->getManager();

        $customerEntity = $entityManager->getRepository(Customer::class)->find($userId);

        if (!$customerEntity) {
            return;
        }

        try {
            $filter = [
                'complex_filter' => [
                    [
                        'key' => 'email',
                        'value' => [
                            'key' => 'eq',
                            'value' => $customerEntity->getEmail()
                        ]
                    ]
                ]
            ];

            $client = $this->getClient();
            $response = $client->callCustomerCustomerList($filter);
            $response = reset($response);          

            $customerEntity->setEmail($response->email);
            $customerEntity->setFirstname($response->firstname);
            $customerEntity->setLastname($response->lastname);
            $customerEntity->setUpdatedAt($this->convertDatetime($response->updated_at));

            $clientAddress = $client->copySession(new AddressSoap());
            $responseAddressList = $clientAddress->callCustomerAddressList($response->customer_id);

            // Remove origin address
            foreach ($customerEntity->getAddress() as $_address) {
                $entityManager->remove($_address);
            }  

            foreach ($responseAddressList as $_address) {
                $addressEntity = new Address;
                $addressEntity->setParent($customerEntity);
                $addressEntity->setCity($_address->city);
                $addressEntity->setCountryId($_address->country_id);
                $addressEntity->setFirstname($_address->firstname);
                $addressEntity->setLastname($_address->lastname);
                $addressEntity->setPostcode($_address->postcode);
                $addressEntity->setPrefix($_address->prefix);
                $addressEntity->setRegion($_address->region);
                $addressEntity->setStreet($_address->street);
                $addressEntity->setTelephone($_address->telephone);
                $addressEntity->setIsDefaultBilling($_address->is_default_billing);
                $addressEntity->setIsDefaultShipping($_address->is_default_shipping);
                $addressEntity->setUpdatedAt($this->convertDatetime($_address->updated_at));

                $entityManager->persist($addressEntity);
            }

            $entityManager->persist($customerEntity);
            $entityManager->flush();            

            $this->success(
                sprintf("Customer #%s, Pull Done", $customerEntity->getEmail())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }             
    }

    /**
     * Return CustomerSoap client.
     * 
     * @return OrderSoap
     */
    private function getClient(): CustomerSoap
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = new CustomerSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? ''); 

        return $client;        
    }
}