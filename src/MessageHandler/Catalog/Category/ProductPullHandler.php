<?php
namespace App\MessageHandler\Catalog\Category;

use App\MessageHandler\MessageHandlerAbstract;
use Symfony\Component\Filesystem\Filesystem;

use App\Api\Magento1x\Soap\Catalog\ProductSoap;
use App\Api\Magento1x\Soap\Catalog\ProductAttributeMediaSoap;
use App\Api\Magento1x\Soap\Catalog\CatalogInventorySoap;

use App\Entity\Product;
use App\Entity\Product\Media;
use App\Entity\Product\CatalogInventory;
use App\Message\Catalog\Category\ProductPull;

use App\Traits\ConfigTrait;

/**
 * Message handler for product pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductPullHandler extends MessageHandlerAbstract
{
    protected static $_soapClient;

	private $apiParams;

	/**
	 * Product handler.
	 * 
	 * @param  ProductPull $productSync
	 * @return void
	 */
    public function __invoke(ProductPull $productSync)
    {
        $productId = $productSync->getProductId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $productEntity = $entityManager->getRepository(Product::class)->find($productId);

        if (!$productEntity) {
            return;
        }

        $client = $this->getClient();
        $product = $client->callCatalogProductInfo($productEntity->getSku());

        $productEntity->setSku($product->sku);
        $productEntity->setTypeId($product->type_id);
        $productEntity->setName($product->name);
        $productEntity->setDescription($product->description);
        $productEntity->setStatus($product->status);
        $productEntity->setUrlPath($product->url_path);
        $productEntity->setProductUrl(sprintf('%s/%s', rtrim($this->apiParams['url'], '/'), $product->url_path));
        $productEntity->setVisibility($product->visibility);
        $productEntity->setHasOptions($product->has_options);
        $productEntity->setPrice($product->price);
        $productEntity->setSpecialPrice($product->special_price ?? 0);
        $productEntity->setUpdatedAt($this->convertDatetime($product->updated_at));

        // Remove origin media
        foreach ($productEntity->getMedia() as $_media) {
        	$entityManager->remove($_media);
        }

        $clientMedia = $client->copySession(new ProductAttributeMediaSoap());
        $mediaList = $clientMedia->callCatalogProductAttributeMediaList($product->product_id);
        foreach ($mediaList as $_media) {
            // Product Media Entity
            $newMediaUrl = $this->downloadMediaFile($_media);
            $productMediaEntity = new Media();

            $productMediaEntity->setProduct($productEntity);
            $productMediaEntity->setFile($_media->file);
            $productMediaEntity->setLabel($_media->label);
            $productMediaEntity->setPosition($_media->position);
            $productMediaEntity->setExclude($_media->exclude);
            $productMediaEntity->setUrl($newMediaUrl);

            $entityManager->persist($productMediaEntity);
        }

        $inventory = $this->getCatalogInventory($productEntity->getSku());
        if ($inventory) {
            $catalogInventoryEntity = $this->getCatalogInventoryEntity($productEntity->getSku());
            $catalogInventoryEntity->setProduct($productEntity);
            $catalogInventoryEntity->setSku($inventory->sku);
            $catalogInventoryEntity->setQty($inventory->qty);
            $catalogInventoryEntity->setIsInStock($inventory->is_in_stock);

            $entityManager->persist($catalogInventoryEntity);
        }                 

        $entityManager->persist($productEntity);
        $entityManager->flush();

        $this->success(
            sprintf("Sku #%s, Done\r\n", $productEntity->getSku())
        );                        
    }

    /**
     * Initialization api params.
     * 
     * @return self
     */
    protected function initialize()
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $this->apiParams = ConfigTrait::configMagentoApi();

    	return $this;
    }

    /**
     * Return catalog inventory entity.
     * 
     * @param  string $sku
     * @return CatalogInventory
     */
    private function getCatalogInventoryEntity(?string $sku): CatalogInventory
    {
        $catalogInventoryEntity = $this->getDoctrine()->getManager()->getRepository(CatalogInventory::class)->findOneBy(['sku' => $sku]);

        if (!$catalogInventoryEntity) {
            $catalogInventoryEntity = new CatalogInventory;
        }

        return $catalogInventoryEntity;
    }

    /**
     * Return catalog inventory.
     * 
     * @param  string $sku
     * @return stdClass
     */
    private function getCatalogInventory(?string $sku)
    {
        $catalogInventory = $this->getClient()
                        ->copySession(new CatalogInventorySoap())
                        ->callCatalogInventoryStockItemList([$sku]);

        return array_shift($catalogInventory);
    }

    /**
     * Return ProductSoap client.
     * 
     * @return ProductSoap
     */
    private function getClient(): ProductSoap
    {
        if (is_null(static::$_soapClient)) {
            static::$_soapClient = new ProductSoap($this->apiParams['url'] ?? '', $this->apiParams['user'] ?? '', $this->apiParams['key'] ?? '');
        }
        
        return static::$_soapClient;
    }

    /**
     * Download meida file.
     *
     * @param  stdClass $meida
     * @return string
     */
    private function downloadMediaFile($meida)
    {
        $filesystem = new Filesystem();
        $targetFile = $this->getParameter()->get('kernel.project_dir') . sprintf('/public/media/catalog/product%s', $meida->file);

        if (!$filesystem->exists(dirname($targetFile))) {
            $filesystem->mkdir(dirname($targetFile));
        }

        $filesystem->copy($meida->url, $targetFile);

        return sprintf('/media/catalog/product%s', $meida->file);
    }        
}