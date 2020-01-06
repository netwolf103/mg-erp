<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product custom option value soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductCustomOptionValueSoap extends SoapAbstract
{
	/**
	 * Add a new custom option value to a custom option.
	 * 
	 * @param  int    $optionId  Option ID
	 * @param  array  $data      Array of catalogProductCustomOptionValueAdd
	 * @param  string $storeView Store view ID or code (optional)
	 * @return boolean           True if the custom option value is added
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOptionValue/product_custom_option_value.add.html
	 */
	public function callCatalogProductCustomOptionValueAdd(int $optionId, array $data, $storeView = null)
	{
		return $this->soapClient->catalogProductCustomOptionValueAdd($this->soapSessionId, $optionId, $data, $storeView);
	}

	/**
	 * Return full information about the specified product custom option value.
	 *
	 * @param  int    $valueId   Value ID
	 * @param  string $storeView Store view ID or code (optional)
	 * @return array             Array of catalogProductCustomOptionValueInfoEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOptionValue/product_custom_option_value.info.html
	 */
	public function callCatalogProductCustomOptionValueInfo(int $valueId, $storeView = null)
	{
		return $this->soapClient->catalogProductCustomOptionValueInfo($this->soapSessionId, $valueId, $storeView);
	}

    /**
     * Return product custom option value list.
     *
     * @param  int    $optionId Option ID
     * @return array            Store view ID or code (optional)
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOptionValue/product_custom_option_value.list.html
     */
    public function callCatalogProductCustomOptionValueList(int $optionId, $storeView = null)
    {
    	return $this->soapClient->catalogProductCustomOptionValueList($this->soapSessionId, $optionId, $storeView);
    }

    /**
     * Remove the custom option value from a product.
     * 
     * @param  int    $valueId Custom option value ID
     * @return boolean|int     True (1) if the custom option value is removed
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOptionValue/product_custom_option_value.remove.html
     */
    public function callCatalogProductCustomOptionValueRemove(int $valueId)
    {
    	return $this->soapClient->catalogProductCustomOptionValueRemove($this->soapSessionId, $valueId);
    }

    /**
     * Update the product custom option value.
     * 
     * @param  int    $valueId   Value ID
     * @param  array  $data      Array of catalogProductCustomOptionValueUpdateEntity
     * @param  string $storeView Store view ID or code (optional)
     * @return boolean            True if the custom option value is updated
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOptionValue/product_custom_option_value.update.html
     */
    public function callCatalogProductCustomOptionValueUpdate(int $valueId, array $data, $storeView = null)
    {
    	return $this->soapClient->catalogProductCustomOptionValueUpdate($this->soapSessionId, $valueId, $data, $storeView);
    }	
}