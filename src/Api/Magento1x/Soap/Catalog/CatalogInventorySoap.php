<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage the stock data.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CatalogInventorySoap extends SoapAbstract
{
	/**
	 * Return the list of stock data by product IDs.
	 * 
	 * @param  array  $productIdsOrSkus List of product IDs or SKUs
	 * @return array                    Array of catalogInventoryStockItemEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalogInventory/cataloginventory_stock_item.list.html
	 */
	public function callCatalogInventoryStockItemList(array $productIdsOrSkus)
	{
		return $this->soapClient->catalogInventoryStockItemList($this->soapSessionId, $productIdsOrSkus);
	}

	/**
	 * Update the required product stock data.
	 * 
	 * @param  string $productIdOrSku Product ID or SKU
	 * @param  array  $data           Array of catalogInventoryStockItemUpdateEntity
	 * @return int                    Result of stock item updating
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalogInventory/cataloginventory_stock_item.update.html
	 */
	public function callCatalogInventoryStockItemUpdate($productIdOrSku, array $data)
	{
		return $this->soapClient->catalogInventoryStockItemUpdate($this->soapSessionId, $productIdOrSku, $data);
	}
}