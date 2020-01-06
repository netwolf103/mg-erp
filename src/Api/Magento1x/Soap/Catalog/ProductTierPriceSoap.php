<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage product tier prices.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductTierPriceSoap extends SoapAbstract
{
	/**
	 * Return information about product tier prices.
	 *
	 * @param  int|string Product ID or SKU
	 * @return array Array of catalogProductTierPriceEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductTierPrice/catalog_product_attribute_tier_price.info.html
	 */
	public function callCatalogProductAttributeTierPriceInfo($productIdOrSku)
	{
		return $this->soapClient->catalogProductAttributeTierPriceInfo($this->soapSessionId, $productIdOrSku);
	}

	/**
	 * Update the product tier prices.
	 *
	 * @param  int|string $productIdOrSku Product ID or SKU
	 * @param  array  $tierPrices         Array of catalogProductTierPriceEntity
	 * @return boolean|int                True (1) if the product tier price is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductTierPrice/catalog_product_attribute_tier_price.update.html
	 */
	public function callCatalogProductAttributeTierPriceUpdate($productIdOrSku, array $tierPrices)
	{
		return $this->soapClient->catalogProductAttributeTierPriceUpdate($this->soapSessionId, $productIdOrSku, $tierPrices);
	}
}