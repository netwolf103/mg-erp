<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage product types.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductTypesSoap extends SoapAbstract
{
	/**
	 * Return the list of product types.
	 * 
	 * @return array
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductTypes/productTypes.html
	 */
	public function callCatalogProductTypes()
	{
		return $this->soapClient->catalogProductTypes($this->soapSessionId);
	}
}