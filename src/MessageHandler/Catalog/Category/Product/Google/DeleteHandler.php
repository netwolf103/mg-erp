<?php
namespace App\MessageHandler\Catalog\Category\Product\Google;

use App\MessageHandler\MessageHandlerAbstract;

use App\Entity\Product\Google as GoogleEntity;
use App\Message\Catalog\Category\Product\Google\Delete;
use App\Traits\ConfigTrait;

/**
 * Message handler for delete google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class DeleteHandler extends MessageHandlerAbstract
{
	const SCOPE = 'https://www.googleapis.com/auth/content';

	/**
	 * Delete google product handler.
	 * 
	 * @param  Delete $delete
	 * @return void
	 */
    public function __invoke(Delete $delete)
    {
        $id = $delete->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $googleEntity = $entityManager->getRepository(GoogleEntity::class)->find($id);

        if (!$googleEntity) {
            return;
        }

        ConfigTrait::loadConfigs($entityManager);
        $merchant_id = ConfigTrait::configGoogleMerchantsId();
        $authConfig  = ConfigTrait::configGoogleAuth();

        if (!ConfigTrait::configGoogleMerchantsEnabled()) {
        	return false;
        }

        $client = new \Google_Client();
        $client->addScope(self::SCOPE);
        $client->setAuthConfig($authConfig);

        $service = new \Google_Service_ShoppingContent($client);

        try {
	        $response = $service->products->delete($merchant_id, $googleEntity->getGId());	        

	        $this->success(
	            sprintf("Product id #%s, Delete Done\r\n", $googleEntity->getId())
	        );        	
        } catch (\Exception $e) {
        	$error = json_decode($e->getMessage());
        	$this->error($error->error->message ?? $e->getMessage());
        } 

        $entityManager->remove($googleEntity);
        $entityManager->flush();                       
    }
}