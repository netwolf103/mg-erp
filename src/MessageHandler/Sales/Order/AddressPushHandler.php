<?php
namespace App\MessageHandler\Sales\Order;

use App\MessageHandler\MessageHandlerAbstract;

use App\Api\Magento1x\Soap\Sales\OrderAddressSoap;

use App\Entity\Sales\Order\Address;
use App\Message\Sales\Order\AddressPush;

use App\Traits\ConfigTrait;

/**
 * Message handler for order address push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressPushHandler extends MessageHandlerAbstract
{
    /**
     * Order address handler.
     * 
     * @param  AddressPush $addressPush
     * @return void
     */
    public function __invoke(AddressPush $addressPush)
    {
        $addressId = $addressPush->getAddressId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $addressEntity = $entityManager->getRepository(Address::class)->find($addressId);

        if (!$addressEntity) {
            return;
        }

        try {
            $client = $this->getClient();
            $client->callSalesOrderAddressUpdate(
                $addressEntity->getParent()->getIncrementId(),
                $addressEntity->getAddressType(),
                [
                    'firstname' => $addressEntity->getFirstname(),
                    'lastname' => $addressEntity->getLastname(),
                    'street' => $addressEntity->getStreet(),
                    'city' => $addressEntity->getCity(),
                    'region' => $addressEntity->getRegion(),
                    'postcode' => $addressEntity->getPostcode(),
                    'country_id' => $addressEntity->getCountryId(),
                    'telephone' => $addressEntity->getTelephone()
                ]
            );
        exit;
            $this->success(
                sprintf("Order address #%s, Push Done", $addressEntity->getParent()->getIncrementId())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }          
    }

    /**
     * Return OrderAddressSoap client.
     * 
     * @return OrderAddressSoap
     */
    private function getClient(): OrderAddressSoap
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $apiParams = ConfigTrait::configMagentoApi();

        $client = new OrderAddressSoap($apiParams['url'] ?? '', $apiParams['user'] ?? '', $apiParams['key'] ?? ''); 

        return $client;        
    }
}