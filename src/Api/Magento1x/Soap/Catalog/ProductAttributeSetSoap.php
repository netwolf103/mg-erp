<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manager product attribute sets.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductAttributeSetSoap extends SoapAbstract
{
	/**
	 * Add an existing attribute to an attribute set.
	 * 
	 * @param  int    $attributeId      Attribute ID
	 * @param  int    $attributeSetId   Attribute set ID
	 * @param  int    $attributeGroupId Group ID (optional)
	 * @param  int    $sortOrder        Sort order (optional)
	 * @return boolean                  True if the attribute is added to an attribute set
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.attributeAdd.html
	 */
	public function callCatalogProductAttributeSetAttributeAdd(int $attributeId, int $attributeSetId, $attributeGroupId = null, $sortOrder = null)
	{
		return $this->soapClient->catalogProductAttributeSetAttributeAdd($this->soapSessionId, $attributeId, $attributeSetId, $attributeGroupId, $sortOrder);
	}

	/**
	 * Remove an existing attribute from an attribute set.
	 * 
	 * @param  int    $attributeId    Attribute ID
	 * @param  int    $attributeSetId Attribute set ID
	 * @return boolean                True if the attribute is removed from an attribute set
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.attributeRemove.html
	 */
	public function callCatalogProductAttributeSetAttributeRemove(int $attributeId, int $attributeSetId)
	{
		return $this->soapClient->catalogProductAttributeSetAttributeRemove($this->soapSessionId, $attributeId, $attributeSetId);
	}

	/**
	 * Create a new attribute set based on another attribute set.
	 * 
	 * @param  string $attributeSetName Attribute set name
	 * @param  int    $skeletonSetId    Attribute set ID basing on which the new attribute set will be created
	 * @return int                      ID of the created attribute set
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.create.html
	 */
	public function callCatalogProductAttributeSetCreate(string $attributeSetName, int $skeletonSetId)
	{
		return $this->soapClient->catalogProductAttributeSetCreate($this->soapSessionId, $attributeSetName, $skeletonSetId);
	}

	/**
	 * Add a new group for attributes to the attribute set.
	 * 
	 * @param  int    $attributeSetId Attribute set ID
	 * @param  string $groupName      Group name
	 * @return int                    ID of the created group
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.groupAdd.html
	 */
	public function callCatalogProductAttributeSetGroupAdd(int $attributeSetId, string $groupName)
	{
		return $this->soapClient->catalogProductAttributeSetGroupAdd($this->soapSessionId, $attributeSetId, $groupName);
	}

	/**
	 * Remove a group from an attribute set.
	 * 
	 * @param  int    $attributeGroupId Group ID
	 * @return boolean|int              True (1) if the group is removed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.groupRemove.html
	 */
	public function callCatalogProductAttributeSetGroupRemove(int $attributeGroupId)
	{
		return $this->soapClient->catalogProductAttributeSetGroupRemove($this->soapSessionId, $attributeGroupId);
	}

	/**
	 * Rename a group in the attribute set.
	 * 
	 * @param  int    $groupId      ID of the group that will be renamed
	 * @param  string $newGroupName New name for the group
	 * @return boolean|int          True (1) if the group is renamed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.groupRename.html
	 */
	public function callCatalogProductAttributeSetGroupRename(int $groupId, string $newGroupName)
	{
		return $this->soapClient->catalogProductAttributeSetGroupRename($this->soapSessionId, $groupId, $newGroupName);
	}

	/**
	 * Return the list of product attribute sets.
	 * 
	 * @return array Array of catalogProductAttributeSetEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.list.html
	 */
	public function callCatalogProductAttributeSetList()
	{
		return $this->soapClient->catalogProductAttributeSetList($this->soapSessionId);
	}

	/**
	 * Remove an existing attribute set.
	 * 
	 * @param  int    $attributeSetId      Attribute set ID
	 * @param  string $forceProductsRemove Force product remove flag (optional)
	 * @return boolean|int                 True (1) if the attribute set is removed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeSet/product_attribute_set.remove.html
	 */
	public function callCatalogProductAttributeSetRemove(int $attributeSetId, $forceProductsRemove = null)
	{
		return $this->soapClient->catalogProductAttributeSetRemove($this->soapSessionId, $attributeSetId, $forceProductsRemove);
	}
}