<?php

namespace App\Api\Magento1x\Soap\Catalog;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product custom option soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductCustomOptionSoap extends SoapAbstract
{
	/**
	 * Add a new custom option for a product.
	 * 
	 * @param  int $productId    Product ID
	 * @param  array  $data      Array of catalogProductCustomOptionToAdd
	 * @param  string $storeView Store view ID or code (optional)
	 * @return boolean           True if the custom option is added
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOption/product_custom_option.add.html
	 */
	public function callCatalogProductCustomOptionAdd(int $productId, array $data, $storeView = null)
	{
		return $this->soapClient->catalogProductCustomOptionAdd($this->soapSessionId, $productId, $data, $storeView);
	}

    /**
     * Return product custom option info.
     *
     * @param  int    $optionId Option ID
     * @param  string $storeView Store view ID or code (optional)
     * @return array  Array of catalogProductCustomOptionInfoEntity
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOption/product_custom_option.info.html
     */
    public function callCatalogProductCustomOptionInfo(int $optionId, $storeView = null)
    {
        return $this->soapClient->catalogProductCustomOptionInfo($this->soapSessionId, $optionId, $storeView);
    }

    /**
     * Return product custom option list.
     *
     * @param  int    $productId Product ID
     * @param  string $storeView Store view ID or code (optional)
     * @return array  Array of catalogProductCustomOptionList
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOption/product_custom_option.list.html
     */
    public function callCatalogProductCustomOptionList(int $productId, $storeView = null)
    {
    	return $this->soapClient->catalogProductCustomOptionList($this->soapSessionId, $productId, $storeView);
    }

    /**
     * Remove a custom option from the product.
     * 
     * @param  int    $optionId Custom option ID
     * @return boolean          True if the custom option is removed
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOption/product_custom_option.remove.html
     */
    public function callCatalogProductCustomOptionRemove(int $optionId)
    {
    	return $this->soapClient->catalogProductCustomOptionRemove($this->soapSessionId, $optionId);
    }

    /**
     * Return the list of available custom option types.
     * 
     * @return array Array of catalogProductCustomOptionTypes
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOption/product_custom_option.types.html
     */
    public function callCatalogProductCustomOptionTypes()
    {
    	return $this->soapClient->catalogProductCustomOptionTypes($this->soapSessionId);
    }

    /**
     * Update the required product custom option.
     * 
     * @param  int    $optionId  Option ID
     * @param  array  $data      Array of catalogProductCustomOptionToUpdate
     * @param  string $storeView Store view ID or code (optional)
     * @return boolean|int       True (1) if the custom option is updated
     * @see https://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductCustomOption/product_custom_option.update.html
     */
    public function callCatalogProductCustomOptionUpdate(int $optionId, array $data, $storeView = null)
    {
    	return $this->soapClient->catalogProductCustomOptionUpdate($this->soapSessionId, $optionId, $data, $storeView);
    }  	
}