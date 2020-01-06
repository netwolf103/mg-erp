<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage categories and how products are assigned to categories.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CatalogCategorySoap extends SoapAbstract
{
	/**
	 * Retrieve the list of products assigned to a required category.
	 *
	 * @param  int $categoryId ID of the required category
	 * @return array           Array of catalogAssignedProduct
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.assignedProducts.html
	 */
	public function callCatalogCategoryAssignedProducts(int $categoryId)
	{
		return $this->soapClient->catalogCategoryAssignedProducts($this->soapSessionId, $categoryId);
	}

	/**
	 * Assign a product to the required category.
	 * 
	 * @param  int    $categoryId     ID of the category
	 * @param  string $productIdOrSKu ID or SKU of the product to be assigned to the category
	 * @param  int    $position       Position of the assigned product in the category (optional)
	 * @return boolean                True if the product is assigned to the specified category
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.assignProduct.html
	 */
	public function callCatalogCategoryAssignProduct(int $categoryId, string $productIdOrSKu, int $position = 0)
	{
		return $this->soapClient->catalogCategoryAssignProduct($this->soapSessionId, $categoryId, $productIdOrSKu, $position);
	}

	/**
	 * Create a new category and return its ID.
	 * 
	 * @param  int    $parentId  Parent category ID
	 * @param  array  $data      Array of catalogCategoryEntityCreate
	 * @param  string $storeView Store view ID or code (optional)
	 * @return int               ID of the created category
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.create.html
	 */
	public function callCatalogCategoryCreate(int $parentId, array $data, $storeView = null)
	{
		return $this->soapClient->catalogCategoryCreate($this->soapSessionId, $parentId, $data, $storeView);
	}

	/**
	 * Set/Get the current store view.
	 * 
	 * @param  string $storeView Store view ID or code
	 * @return string            Store view ID
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.currentStore.html
	 */
	public function callCatalogCategoryCurrentStore($storeView = null)
	{
		return $this->soapClient->catalogCategoryCurrentStore($this->soapSessionId, $storeView);
	}

	/**
	 * Delete the required category.
	 * 
	 * @param  int    $categoryId ID of the category to be deleted
	 * @return boolean            True if the category is deleted
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.delete.html
	 */
	public function callCatalogCategoryDelete(int $categoryId)
	{
		return $this->soapClient->catalogCategoryDelete($this->soapSessionId, $categoryId);
	}

	/**
	 * Return information about the required category.
	 * @param  int    $categoryId Category ID
	 * @param  string $storeView  Store view ID or code (optional)
	 * @param  array  $attributes Array of attributes (optional)
	 * @return array              Array of catalogCategoryInfo
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.info.html
	 */
	public function callCatalogCategoryInfo(int $categoryId, $storeView = null, array $attributes = [])
	{
		return $this->soapClient->catalogCategoryInfo($this->soapSessionId, $categoryId, $storeView, $attributes);
	}

	/**
	 * Return one level of categories by a website, a store view, or a parent category.
	 * 
	 * @param  int    $parentId        Parent category ID (optional)
	 * @param  string $websiteIdOrCode Website ID or code (optional)
	 * @param  string $storeView       Store view ID or code (optional)
	 * @return array                   Array of CatalogCategoryEntitiesNoChildren
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.level.html
	 */
	public function callCatalogCategoryLevel(int $parentId = 0, $websiteIdOrCode = null, $storeView = null)
	{
		return $this->soapClient->catalogCategoryLevel($this->soapSessionId, $websiteIdOrCode, $storeView, $parentId);
	}

	/**
	 * Move the required category in the category tree.
	 * 
	 * @param  int    $categoryId ID of the category to be moved
	 * @param  int    $parentId   ID of the new parent category
	 * @return boolean            True if the category is moved
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.move.html
	 */
	public function callCatalogCategoryMove(int $categoryId, int $parentId)
	{
		return $this->soapClient->catalogCategoryMove($this->soapSessionId, $categoryId, $parentId);
	}

	/**
	 * Remove the product assignment from the category.
	 * 
	 * @param  int    $categoryId     Category ID
	 * @param  string $productIdOrSKu ID or SKU of the product to be removed from the category
	 * @return boolean                True if the product is removed from the category
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.removeProduct.html
	 */
	public function callCatalogCategoryRemoveProduct(int $categoryId, $productIdOrSKu)
	{
		return $this->soapClient->catalogCategoryRemoveProduct($this->soapSessionId, $categoryId, $productIdOrSKu);
	}

	/**
	 * Return  the hierarchical tree of categories.
	 * 
	 * @param  int    $parentId  ID of the parent category (optional)
	 * @param  string $storeView Store view (optional)
	 * @return array             Array of catalogCategoryTree
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.tree.html
	 */
	public function callCatalogCategoryTree(int $parentId, $storeView = null)
	{
		return $this->soapClient->catalogCategoryTree($this->soapSessionId, $parentId, $storeView);
	}

	/**
	 * Update the required category.
	 * 
	 * @param  int    $categoryId ID of the category to be updated
	 * @param  array  $data       An array of catalogCategoryEntityCreate
	 * @param  string $storeView  Store view ID or code (optional)
	 * @return boolean            True if the category is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.update.html
	 */
	public function callCatalogCategoryUpdate(int $categoryId, array $data, $storeView = null)
	{
		return $this->soapClient->catalogCategoryUpdate($this->soapSessionId, $categoryId, $data, $storeView);
	}

	/**
	 * Update the product assigned to a category. 
	 * 
	 * @param  int         $categoryId     ID of the category to which the product is assigned
	 * @param  string      $productIdOrSku ID or SKU of the product to be updated
	 * @param  int|integer $position       Position of the product in the category (optional)
	 * @return boolean                     True if the product is updated in the category
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogCategory/catalog_category.updateProduct.html
	 */
	public function callCatalogCategoryUpdateProduct(int $categoryId, $productIdOrSku, int $position = 0)
	{
		return $this->soapClient->catalogCategoryUpdateProduct($this->soapSessionId, $categoryId, $productIdOrSku, $position);
	}
}