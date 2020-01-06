<?php

namespace App\Api\Magento1x\Soap\Checkout;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage products in a shopping cart.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductSoap extends SoapAbstract
{
	/**
	 * Add one or more products to the shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID (quote ID) 
	 * @param  array  $data      An array with the list of shoppingCartProductEntity 
	 * @param  int|string $storeView Store view ID or code (optional) 
	 * @return boolean           True on success
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartProduct/cart_product.add.html
	 */
	public function callShoppingCartProductAdd(int $quoteId, array $data, $storeView = null)
	{
		return $this->soapClient->shoppingCartProductAdd($this->soapSessionId, $quoteId, $data, $storeView);
	}

	/**
	 * Return the list of products in the shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return array             Array of shoppingCartProductResponseEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartProduct/cart_product.list.html
	 */
	public function callShoppingCartProductList(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartProductList($this->soapSessionId, $quoteId, $storeView);
	}

	/**
	 * Remove one or several products from a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  array  $data      Array of shoppingCartProductEntity
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return boolean           True if the product is removed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartProduct/cart_product.remove.html
	 */
	public function callShoppingCartProductRemove(int $quoteId, array $data, $storeView = null)
	{
		return $this->soapClient->shoppingCartProductRemove($this->soapSessionId, $quoteId, $data, $storeView);
	}

	/**
	 * Update one or several products in the shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  array  $data      Array of shoppingCartProductEntity
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return boolean           True if the product is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartProduct/cart_product.update.html
	 */
	public function callShoppingCartProductUpdate(int $quoteId, array $data, $storeView = null)
	{
		return $this->soapClient->shoppingCartProductUpdate($this->soapSessionId, $quoteId, $data, $storeView);
	}
}