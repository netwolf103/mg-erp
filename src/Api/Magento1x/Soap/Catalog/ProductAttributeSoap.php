<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage product attributes and options.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductAttributeSoap extends SoapAbstract
{
	/**
	 * Add a new option for attributes with selectable fields.
	 * 
	 * @param  string $attributeIdOrCode Attribute code or ID
	 * @param  array  $data              Array of catalogProductAttributeOptionEntityToAdd
	 * @return boolean                   True on success
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.addOption.html
	 */
	public function callCatalogProductAttributeAddOption($attributeIdOrCode, array $data)
	{
		return $this->soapClient->catalogProductAttributeAddOption($this->soapSessionId, $attributeIdOrCode, $data);
	}

	/**
	 * Create a new product attribute.
	 * 
	 * @param  array  $data Array of catalogProductAttributeEntityToCreate
	 * @return int          ID of the created attribute
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.create.html
	 */
	public function callCatalogProductAttributeCreate(array $data)
	{
		return $this->soapClient->catalogProductAttributeCreate($this->soapSessionId, $data);
	}

	/**
	 * Set/Get the current store view.
	 * 
	 * @param  string $storeView Store view ID or code (optional)
	 * @return int               Store view ID
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.currentStore.html
	 */
	public function callCatalogProductAttributeCurrentStore($storeView = null)
	{
		return $this->soapClient->catalogProductAttributeCurrentStore($this->soapSessionId, $storeView);
	}

	/**
	 * Get full information about a required attribute with the list of options.
	 * 
	 * @param  string $attributeIdOrCode Attribute code or ID
	 * @return array                     Array of catalogProductAttributeEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.info.html
	 */
	public function callCatalogProductAttributeInfo($attributeIdOrCode)
	{
		return $this->soapClient->catalogProductAttributeInfo($this->soapSessionId, $attributeIdOrCode);
	}

	/**
	 * Return the list of product attributes.
	 * 
	 * @param  int    $attributeSetId ID of the attribute set
	 * @return array                  Array of catalogAttributeEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.list.html
	 */
	public function callCatalogProductAttributeList(int $attributeSetId)
	{
		return $this->soapClient->catalogProductAttributeList($this->soapSessionId, $attributeSetId);
	}

	/**
	 * Return the product attribute options.
	 * 
	 * @param  string $attributeIdOrCode Attribute ID or code
	 * @param  string $storeView         Store view ID or code (optional)
	 * @return array                     Array of catalogAttributeOptionEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.options.html
	 */
	public function callCatalogProductAttributeOptions($attributeIdOrCode, $storeView = null)
	{
		return $this->soapClient->catalogProductAttributeOptions($this->soapSessionId, $attributeIdOrCode, $storeView);
	}

	/**
	 * Remove the required attribute from a product.
	 * 
	 * @param  string $attributeIdOrCode Attribute code or ID
	 * @return boolean                   True if the attribute is removed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.remove.html
	 */
	public function callCatalogProductAttributeRemove($attributeIdOrCode)
	{
		return $this->soapClient->catalogProductAttributeRemove($this->soapSessionId, $attributeIdOrCode);
	}

	/**
	 * Remove the option for an attribute.
	 * 
	 * @param  string $attributeIdOrCode Attribute code or ID
	 * @param  int    $optionId          Option ID
	 * @return boolean                   True if the option is removed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.removeOption.html
	 */
	public function callCatalogProductAttributeRemoveOption($attributeIdOrCode, int $optionId)
	{
		return $this->soapClient->catalogProductAttributeRemoveOption($this->soapSessionId, $attributeIdOrCode, $optionId);
	}

	/**
	 * Return the list of possible attribute types.
	 * 
	 * @return array Array of catalogAttributeOptionEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.types.html
	 */
	public function callCatalogProductAttributeTypes()
	{
		return $this->soapClient->catalogProductAttributeTypes($this->soapSessionId);
	}

	/**
	 * Update the required attribute.
	 * 
	 * @param  string $attributeIdOrCode Attribute code or ID
	 * @param  arrray $data              Array of catalogProductAttributeEntityToUpdate
	 * @return boolean                   True if the attribute is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttribute/product_attribute.update.html
	 */
	public function callCatalogProductAttributeUpdate($attributeIdOrCode, arrray $data)
	{
		return $this->soapClient->catalogProductAttributeUpdate($this->soapSessionId, $attributeIdOrCode, $data);
	}
}