<?php
namespace App\MessageHandler\Sales\Order\Address;

use App\MessageHandler\MessageHandlerAbstract;

use App\Message\Sales\Order\Address\GeoPull;
use App\Api\Geosearch\AddressSearch;
use App\Entity\Sales\Order\Address;
use App\Entity\Sales\Order\Address\Geo;

/**
 * Message handler for address geo pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GeoPullHandler extends MessageHandlerAbstract
{
    /**
     * Address geo pull handler.
     * 
     * @param  GeoPull $geoPull
     * @return void
     */
    public function __invoke(GeoPull $geoPull)
    {
        $addressId = $geoPull->getAddressId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $addressEntity = $entityManager->getRepository(Address::class)->find($addressId);

        if (!$addressEntity) {
            return;
        }

        try {
            if (!$addressEntity->getGeos()->isEmpty()) {
                throw new \Exception('Geo data existed.');
            }

            $client = $this->getClient();
            $response = $client->search([
                'county' => $addressEntity->getCountryId(),
                'postalcode' => $addressEntity->getPostcode(),
                'state' => $addressEntity->getRegion(),
                //'city' => $addressEntity->getCity(),
                'street' => $addressEntity->getStreet(),
            ]);
            
            if (!$response) {
                $addressEntity->setIsWrong(true);
                $entityManager->flush();                
                throw new \Exception('Geo data not found.');
            }

            foreach ($response as $geo) {
                $geoEntity = new Geo;
                $geoEntity->setParent($addressEntity);
                $geoEntity->setLat($geo->lat);
                $geoEntity->setLon($geo->lon);
                $geoEntity->setDisplayName($geo->display_name);
                $geoEntity->setClass($geo->class);
                $geoEntity->setType($geo->type);
                $geoEntity->setImportance($geo->importance);

                $entityManager->persist($geoEntity);

                $entityManager->flush();
            }         
        
            $this->success(
                sprintf("Order address #%d, Pull Done", $addressEntity->getId())
            );
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }         
    }

    /**
     * Return AddressSearch client.
     * 
     * @return AddressSearch
     */
    private function getClient(): AddressSearch
    {
        $client = new AddressSearch(); 

        return $client;        
    }
}