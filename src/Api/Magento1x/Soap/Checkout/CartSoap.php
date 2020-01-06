<?php

namespace App\Api\Magento1x\Soap\Checkout;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage shopping carts.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CartSoap extends SoapAbstract
{
	/**
	 * Create an empty shopping cart.
	 * 
	 * @param  string $storeView Store view ID or code (optional)
	 * @return int               ID of the created empty shopping cart
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cart/cart.create.html
	 */
	public function callShoppingCartCreate($storeView = null)
	{
		return $this->soapClient->shoppingCartCreate($this->soapSessionId, $storeView);
	}

	/**
	 * Return full information about the shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID (quote ID)
	 * @param  string $storeView Store view ID or code (optional)
	 * @return array             Array of shoppingCartInfoEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cart/cart.info.html
	 */
	public function callShoppingCartInfo(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartInfo($this->soapSessionId, $quoteId, $storeView);
	}

	/**
	 * Return the website license agreement for the quote according to the website (store).
	 * 
	 * @param  int    $quoteId   Shopping cart ID (quote identifier)
	 * @param  string $storeView Store view ID or code (optional)
	 * @return array             Array of shoppingCartLicenseEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cart/cart.license.html
	 */
	public function callShoppingCartLicense(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartLicense($this->soapSessionId, $quoteId, $storeView);
	}

	/**
	 * Create an order from a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping Cart ID (quote ID)
	 * @param  string $storeView Store view ID or code (optional)
	 * @param  int $licenseId    Website license ID (optional)
	 * @return string            Result of creating an order
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cart/cart.order.html
	 */
	public function callShoppingCartOrder(int $quoteId, $storeView = null, $licenseId = null)
	{
		return $this->soapClient->shoppingCartOrder($this->soapSessionId, $quoteId, $storeView, $licenseId);
	}

	/**
	 * Return total prices for a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID (quote identifier)
	 * @param  string $storeView Store view ID or code (optional)
	 * @return array             Array of shoppingCartTotalsEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cart/cart.totals.html
	 */
	public function callShoppingCartTotals(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartTotals($this->soapSessionId, $quoteId, $storeView);
	}
}