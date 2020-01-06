<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductSoap extends SoapAbstract
{
    /**
     * Create a new product.
     * 
     * @param  string $productType    Product type
     * @param  int    $attributeSetId ID of the product attribute set
     * @param  string $sku            Product SKU
     * @param  array  $productData    Array of catalogProductCreateEntity
     * @param  int|string $storeView  Store view ID or code
     * @return int                    ID of the created product
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.create.html
     */
    public function callCatalogProductCreate(string $productType, int $attributeSetId, string $sku, array $productData, $storeView = null)
    {
        return $this->soapClient->catalogProductCreate($this->soapSessionId, $productType, $attributeSetId, $sku, $productData, $storeView);
    }

    /**
     * Set/Get the current store view.
     * 
     * @param  string $storeView Store view ID or code (optional)
     * @return int|string        Store view ID
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.currentStore.html
     */
    public function callCatalogProductCurrentStore($storeView = null)
    {
        return $this->soapClient->catalogProductCurrentStore($this->soapSessionId, $storeView);
    }

    /**
     * Delete the required product.
     *
     * @param  string $productIdOrSku  Product ID or SKU
     * @return boolean|int                 True (1) if the product is deleted
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.delete.html
     */
    public function callCatalogProductDelete(string $productIdOrSku)
    {
        return $this->soapClient->catalogProductDelete($this->soapSessionId, $productIdOrSku);
    }

    /**
     * Get the product special price data.
     * 
     * @param  string $productIdOrSku Product ID or SKU
     * @return array                      Array of catalogProductReturnEntity
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.getSpecialPrice.html
     */
    public function callCatalogProductGetSpecialPrice(string $productIdOrSku)
    {
        return $this->soapClient->catalogProductGetSpecialPrice($this->soapSessionId, $productIdOrSku);
    }

    /**
     * Set the product special price.
     * 
     * @param  string $productIdOrSku Product ID or SKU
     * @param  float $specialPrice    Product special price
     * @param  string $fromDate       Date starting from which special price will be applied
     * @param  string $toDate         Date till which special price will be applied
     * @param  string $storeView      Store view ID or code (optional)
     * @return boolean|int            True (1) if the special price is set for the product
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.setSpecialPrice.html
     */
    public function callCatalogProductSetSpecialPrice(string $productIdOrSku, $specialPrice, $fromDate, $toDate, $storeView = null)
    {
        return $this->soapClient->catalogProductSetSpecialPrice($this->soapSessionId, $productIdOrSku, $specialPrice, $fromDate, $toDate, $storeView);
    }

    /**
     * Return product list.
     * 
     * @param  array  $filters       Array of filters by attributes (optional)
     * @param  int|string $storeView Store view ID or code (optional)
     * @return array                 Array of catalogProductEntity
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.list.html
     */
    public function callCatalogProductList(array $filters = [], $storeView = null)
    {
        return $this->soapClient->catalogProductList($this->soapSessionId, $filters, $storeView);
    }

    /**
     * Return product base info.
     *
     * @param  string $productIdOrSku Product ID or SKU
     * @return array  Array of catalogProductReturnEntity
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.info.html
     */
    public function callCatalogProductInfo(string $productIdOrSku, $storeView = null, array $attributes = [], array $additionalAttributes = ['stonecolor', 'platingcolor', 'stonecut', 'primarystonecarat', 'metal', 'product_source'])
    {
        $attributes = new \stdclass();
        $attributes->attributes  = $attributes;
        $attributes->additional_attributes  = $additionalAttributes;
        
        return $this->soapClient->catalogProductInfo($this->soapSessionId, $productIdOrSku, $storeView, $attributes);
    }

    /**
     * Get the list of additional attributes.
     * 
     * @param  string $productType    Product type (e.g., simple)
     * @param  int    $attributeSetId Attribute set ID
     * @return array
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.listOfAdditionalAttributes.html
     */
    public function callCatalogProductListOfAdditionalAttributes(string $productType, int $attributeSetId)
    {
        return $this->soapClient->catalogProductListOfAdditionalAttributes($this->soapSessionId, $productType, $attributeSetId);
    }

    /**
     * Update the required product.
     * 
     * @param  int    $productId   Product ID
     * @param  array  $productData Array of catalogProductCreateEntity
     * @param  int|string $storeView   Store view ID or code (optional)
     * @return boolean              True if the product is updated
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.update.html
     */
    public function callCatalogProductUpdate(int $productId, array $productData, $storeView = null)
    {
        return $this->soapClient->catalogProductUpdate($this->soapSessionId, $productId, $productData, $storeView);
    }
}