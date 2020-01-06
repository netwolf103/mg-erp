<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage links for products, including related, cross-sells, up-sells, and grouped.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductLinkSoap extends SoapAbstract
{
	/**
	 * Assign a product link (cross_sell, grouped, related, or up_sell) to another product.
	 * 
	 * @param  string $type                 ype of the link (cross_sell, grouped, related, or up_sell)
	 * @param  string $productIdOrSku       Product ID or SKU
	 * @param  string $linkedProductIdOrSku Product ID or SKU for the link
	 * @param  array  $data                 Array of catalogProductLinkEntity
	 * @return boolean                      True if the link is assigned to the product
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductLink/catalog_product_link.assign.html
	 */
	public function callCatalogProductLinkAssign(string $type, $productIdOrSku, $linkedProductIdOrSku, array $data)
	{
		return $this->soapClient->catalogProductLinkAssign($this->soapSessionId, $type, $productIdOrSku, $linkedProductIdOrSku, $data);
	}

	/**
	 * Return the product link type attributes.
	 * 
	 * @param  string $type Type of the link (cross_sell, up_sell, related, or grouped)
	 * @return array        Array of catalogProductLinkAttributeEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductLink/catalog_product_link.attributes.html
	 */
	public function callCatalogProductLinkAttributes(string $type)
	{
		return $this->soapClient->catalogProductLinkAttributes($this->soapSessionId, $type);
	}

	/**
	 * Return the list of linked products for a specific product.
	 * 
	 * @param  string $type           Type of the link (cross_sell, up_sell, related, or grouped)
	 * @param  string $productIdOrSku Product ID or SKU
	 * @return array                  Array of catalogProductLinkEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductLink/catalog_product_link.list.html
	 */
	public function callCatalogProductLinkList(string $type, $productIdOrSku)
	{
		return $this->soapClient->catalogProductLinkList($this->soapSessionId, $type, $productIdOrSku);
	}

	/**
	 * Remove the product link from a specific product.
	 * 
	 * @param  string $type                 Type of the link (cross_sell, up_sell, related, or grouped)
	 * @param  string $productIdOrSku       Product ID or SKU
	 * @param  string $linkedProductIdOrSku Product ID or SKU for the link
	 * @return boolean|int                  True (1) if the link is removed from a product
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductLink/catalog_product_link.remove.html
	 */
	public function callCatalogProductLinkRemove(string $type, $productIdOrSku, $linkedProductIdOrSku)
	{
		return $this->soapClient->catalogProductLinkRemove($this->soapSessionId, $type, $productIdOrSku, $linkedProductIdOrSku);
	}

	/**
	 * Return the list of product link types.
	 * 
	 * @return array Array of link types
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductLink/catalog_product_link.types.html
	 */
	public function callCatalogProductLinkTypes()
	{
		return $this->soapClient->catalogProductLinkTypes($this->soapSessionId);
	}

	/**
	 * Update the product link.
	 * 
	 * @param  string $type                 Type of the link (cross_sell, grouped, related, or up_sell)
	 * @param  string $productIdOrSku       Product ID or SKU
	 * @param  string $linkedProductIdOrSku Product ID or SKU for the link
	 * @param  array  $data                 Array of catalogProductLinkEntity
	 * @return boolean|int                  True (1) if the link is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductLink/catalog_product_link.update.html
	 */
	public function callCatalogProductLinkUpdate(string $type, $productIdOrSku, $linkedProductIdOrSku, array $data)
	{
		return $this->soapClient->catalogProductLinkUpdate($this->soapSessionId, $type, $productIdOrSku, $linkedProductIdOrSku, $data);
	}
}