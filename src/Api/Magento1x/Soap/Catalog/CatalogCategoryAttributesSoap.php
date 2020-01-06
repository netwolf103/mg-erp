<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Retrieve the list of category attributes and options.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CatalogCategoryAttributesSoap extends SoapAbstract
{
	/**
	 * Set/Get the current store view.
	 * 
	 * @param  string $storeView Store view ID or code
	 * @return int               Store view ID
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategoryAttributes/catalog_category_attribute.currentStore.html
	 */
	public function callCatalogCategoryAttributeCurrentStore($storeView)
	{
		return $this->soapClient->catalogCategoryAttributeCurrentStore($this->soapSessionId, $storeView);
	}

	/**
	 * Return the list of category attributes.
	 * 
	 * @return array Array of catalogAttributeEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategoryAttributes/catalog_category_attribute.list.html
	 */
	public function callCatalogCategoryAttributeList()
	{
		return $this->soapClient->catalogCategoryAttributeList($this->soapSessionId);
	}

	/**
	 * Return the attribute options.
	 * 
	 * @param  string $attributeIdOrCode Attribute ID or code
	 * @param  string $storeView         Store view ID or code
	 * @return array                     Array of catalogAttributeOptionEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategoryAttributes/catalog_category_attribute.options.html
	 */
	public function callCatalogCategoryAttributeOptions($attributeIdOrCode, $storeView = null)
	{
		return $this->soapClient->catalogCategoryAttributeOptions($this->soapSessionId, $attributeIdOrCode, $storeView);
	}
}