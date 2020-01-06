<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product media soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductAttributeMediaSoap extends SoapAbstract
{
	/**
	 * Set/Get the current store view.
	 * 
	 * @param  string $storeView Store view ID or code (optional)
	 * @return int               Store view ID
	 */
	public function callCatalogProductAttributeMediaCurrentStore($storeView = null)
	{
		return $this->soapClient->catalogProductAttributeMediaCurrentStore($this->soapSessionId, $storeView);
	}

	/**
	 * Upload a new product image
	 * 
	 * @param  int    $productId     Product ID
	 * @param  array $data           Array of catalogProductAttributeMediaCreateEntity
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return string                Image file name (e.g., "/i/m/image.png")
	 */
	public function callCatalogProductAttributeMediaCreate(int $productId, $data, $storeView = null)
	{
		return $this->soapClient->catalogProductAttributeMediaCreate($this->soapSessionId, $productId, $data, $storeView);
	}

	/**
	 * Return product media info.
	 * 
	 * @param  string $productIdOrSku Product ID or SKU
	 * @param  string $fileName           Name of the image file (e.g., /b/l/blackberry8100_2.jpg)
	 * @param  int|string $storeView      Store view ID or code (optional)
	 * @return array                      Array of catalogProductImageEntity
	 */
	public function callCatalogProductAttributeMediaInfo(string $productIdOrSku, $fileName, $storeView = null)
	{
		return $this->soapClient->catalogProductAttributeMediaInfo($this->soapSessionId, $productIdOrSku, $fileName, $storeView);
	}

	/**
	 * Return product media list.
	 * 
	 * @param  string $productIdOrSku Product ID or SKU
	 * @param  int|string $storeView      Store view ID or code (optional)
	 * @return array                      Array of catalogProductImageEntity
	 */
	public function callCatalogProductAttributeMediaList(string $productIdOrSku, $storeView = null)
	{
		return $this->soapClient->catalogProductAttributeMediaList($this->soapSessionId, $productIdOrSku, $storeView);
	}

	/**
	 * Remove the image from a product.
	 * 
	 * @param  string $productIdOrSku  Product ID or SKU
	 * @param  string $fileName       	   Image file name (e.g., /b/l/blackberry8100_2.jpg)
	 * @return boolean|int                 True (1) if the image is removed from a product
	 */
	public function callCatalogProductAttributeMediaRemove(string $productIdOrSku, string $fileName)
	{
		return $this->soapClient->catalogProductAttributeMediaRemove($this->soapSessionId, $productIdOrSku, $fileName);
	}

	/**
	 * Return product image types including standard image, small_image, thumbnail.
	 * 
	 * @param  int    $arrributeSetId 	ID of the product attribute set
	 * @return array                    Array of catalogProductAttributeMediaTypeEntity
	 */
	public function callCatalogProductAttributeMediaTypes(int $arrributeSetId)
	{
		return $this->soapClient->catalogProductAttributeMediaTypes($this->soapSessionId, $arrributeSetId);
	}

	/**
	 * Update the product image.
	 * 
	 * @param  int $productId    Product ID
	 * @param  string $fileName  Image file name (e.g., /i/m/image.jpeg)
	 * @param  array  $data      Array of catalogProductAttributeMediaCreateEntity
	 * @param  string $storeView Store view ID or code
	 * @return boolean           Result of product image updating
	 */
	public function callCatalogProductAttributeMediaUpdate($productId, string $fileName, array $data, $storeView = null)
	{
		return $this->soapClient->catalogProductAttributeMediaUpdate($this->soapSessionId, $productId, $fileName, $data, $storeView);
	}
}