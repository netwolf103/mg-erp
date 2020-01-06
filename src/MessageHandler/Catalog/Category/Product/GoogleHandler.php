<?php
namespace App\MessageHandler\Catalog\Category\Product;

use App\MessageHandler\MessageHandlerAbstract;

use App\Entity\Product;
use App\Entity\Product\CatalogInventory;
use App\Entity\Product\Google as GoogleEntity;
use App\Message\Catalog\Category\Product\Google;

use App\Api\Magento1x\Soap\Catalog\ProductSoap;
use App\Api\Magento1x\Soap\Catalog\ProductAttributeSoap;
use App\Api\Magento1x\Soap\Catalog\ProductAttributeMediaSoap;

use App\Traits\ConfigTrait;

/**
 * Message handler for create google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GoogleHandler extends MessageHandlerAbstract
{
    protected static $_soapClient;

    private $apiParams;

	/**
	 * Create google product handler.
	 * 
	 * @param  Google $google
	 * @return void
	 */
    public function __invoke(Google $google)
    {
        $productId = $google->getProductId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $productEntity = $entityManager->getRepository(Product::class)->find($productId);

        if (!$productEntity) {
            return;
        }

        try {
            $sizes = [];
            foreach ($productEntity->getOptionSizes() as $_size) {
                $sizes[] = $_size->getTitle();
            }

            $additionalAttributes = $this->getAdditionalAttributes($productEntity->getSku());
            $imageLinks           = $this->getImageLinks($productEntity->getSku());

            $googleEntity = $productEntity->getGoogle();
            if (!$googleEntity) {
                $googleEntity = new GoogleEntity;
            }

            $googleEntity->setParent($productEntity);
            $googleEntity->setGOfferId($productEntity->getSku());
            $googleEntity->setGTitle($productEntity->getName());
            $googleEntity->setGDescription($productEntity->getDescription());
            $googleEntity->setGLink($productEntity->getProductUrl());
            $googleEntity->setGImageLink(array_shift($imageLinks));
            $googleEntity->setGAdditionalImageLink(json_encode($imageLinks));
            $googleEntity->setGAvailability($this->getAvailability($productEntity));
            $googleEntity->setGPrice($this->getPrice($productEntity->getPrice()));
            $googleEntity->setGSalePrice($this->getPrice($productEntity->getFinalPrice()));
            $googleEntity->setGBrand($this->getBrand());
            $googleEntity->setGGoogleProductCategoryId(200);
            $googleEntity->setGIdentifierExists(true);
            $googleEntity->setGGtin($this->getGtin($productEntity));
            $googleEntity->setGMpn($productEntity->getSku());
            $googleEntity->setGCondition(GoogleEntity::CONDITION_NEW);
            $googleEntity->setGAdult(false);
            $googleEntity->setGIsBundle(false);
            $googleEntity->setGAgeGroup(GoogleEntity::AGE_GROUP_ADULT);
            $googleEntity->setGColor($this->getAttributeOptions('stonecolor', $additionalAttributes['stonecolor'] ?? null));
            $googleEntity->setGGender(GoogleEntity::GENDER_FEMALE);
            $googleEntity->setGMaterial($additionalAttributes['metal'] ?? '');
            $googleEntity->setGPattern($this->getAttributeOptions('stonecut', $additionalAttributes['stonecut'] ?? null));
            $googleEntity->setGSize(json_encode($sizes));
            $googleEntity->setGItemGroupId($productEntity->getSku());
            //$googleEntity->setGShipping($this->getPrice(0));
            //$googleEntity->setGTax('no');

            $entityManager->persist($googleEntity);
            $entityManager->flush();        

            $this->success(
                sprintf("Product id #%s, Done\r\n", $productEntity->getId())
            );              
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }                 
    }

    /**
     * Initialization api params.
     * 
     * @return self
     */
    protected function initialize(): self
    {
        ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
        $this->apiParams = ConfigTrait::configMagentoApi();

        return $this;
    }

    /**
     * Get brand name.
     * 
     * @return string
     */
    private function getBrand(): string
    {
        return ConfigTrait::configBrand();
    }

    /**
     * Get product additional attributes.
     * 
     * @param  string $sku
     * @return array
     */
    private function getAdditionalAttributes(string $sku): array
    {
        $product = $this->getClient()->callCatalogProductInfo($sku);

        $additionalAttributes = $product->additional_attributes ?? [];

        return array_column($additionalAttributes, 'value', 'key');
    }

    /**
     * Return image links of product.
     * 
     * @param  string $sku
     * @return array
     */
    private function getImageLinks(string $sku): array
    {
        $imageLinks = [];

        $medias = $this->getClient()->copySession(new ProductAttributeMediaSoap())->callCatalogProductAttributeMediaList($sku);
        foreach ($medias as $media) {
            if (isset($media->url) && !empty($media->url)) {
                $imageLinks[] = $media->url;
            }
        }

        return $imageLinks;
    }

    /**
     * Return product attribute options form soap.
     * @param  string $attributeCode
     * @param  int    $optionId
     * @return array
     */
    private function getAttributeOptions(string $attributeCode, ?int $optionId)
    {
        if (!$optionId) {
            return '';
        }

        $value = '';
        $options = $this->getClient()->copySession(new ProductAttributeSoap())->callCatalogProductAttributeOptions($attributeCode);
        foreach ($options as $option) {
            if ($option->value == $optionId) {
                $value = $option->label;
                break;
            }
        }

        return $value;
    }

    /**
     * Return ProductAttributeMediaSoap client.
     * 
     * @return ProductAttributeMediaSoap
     */
    private function getClient(): ProductSoap
    {
        if (is_null(static::$_soapClient)) {
            static::$_soapClient = new ProductSoap($this->apiParams['url'] ?? '', $this->apiParams['user'] ?? '', $this->apiParams['key'] ?? '');
        }
        
        return static::$_soapClient;
    }

    /**
     * Return gtin code.
     * 
     * @param  Product $product
     * @return string
     */
    private function getGtin(Product $product): string
    {
        return '';
    }  

    /**
     * Return availability.
     * 
     * @param  Product $product
     * @return string
     */
    private function getAvailability(Product $product): string
    {
        if (!$product->getCatalogInventory()) {
            return GoogleEntity::AVAILABILITY_OUT_OF_STOCK;
        }

        return ($product->getStatus() == Product::STATUS_ENABLED && $product->getCatalogInventory()->getIsInStock() == CatalogInventory::IN_STOCK) ? GoogleEntity::AVAILABILITY_IN_STOCK : GoogleEntity::AVAILABILITY_OUT_OF_STOCK;
    }

    /**
     * Return price.
     * 
     * @param  float  $price
     * @param  string $currency
     * @return string
     */
    private function getPrice(float $price, string $currency = 'USD'): string
    {
        return sprintf('%s %s', $price, $currency);
    }   
}