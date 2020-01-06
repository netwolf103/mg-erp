<?php

namespace App\Api\Magento1x\Soap\Checkout;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Mshipping methods for a shopping cart.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShippingSoap extends SoapAbstract
{	
	/**
	 * Return the list of available shipping methods for a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return array             Array of shoppingCartShippingMethodEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartShipping/cart_shipping.list.html
	 */
	public function callShoppingCartShippingList(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartShippingList($this->soapSessionId, $quoteId, $storeView);
	}

	/**
	 * Set a shipping method for a shopping cart (quote).
	 * 
	 * @param  int    $quoteId    Shopping cart ID
	 * @param  string $methodCode Shipping method code
	 * @param  int|string $storeView  Store view ID or code (optional)
	 * @return boolean            True if the shipping method is set
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartShipping/cart_shipping.method.html
	 */
	public function callShoppingCartShippingMethod(int $quoteId, string $methodCode, $storeView = null)
	{
		return $this->soapClient->shoppingCartShippingMethod($this->soapSessionId, $quoteId, $methodCode, $storeView);
	}
}