<?php

namespace App\Api\Magento1x\Soap\Checkout;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Retrieve and set payment methods for a shopping cart.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PaymentSoap extends SoapAbstract
{
	/**
	 * Set a payment method for a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  array  $methods   Array of shoppingCartPaymentMethodEntity
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return boolean           True on success
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartPayment/cart_payment.method.html
	 */
	public function callShoppingCartPaymentMethod(int $quoteId, array $methods, $storeView = null)
	{
		return $this->soapClient->shoppingCartPaymentMethod($this->soapSessionId, $quoteId, $methods, $storeView);
	}

	/**
	 * Return a list of available payment methods for a shopping cart (quote).
	 * 
	 * @param  int         $quoteId   Shopping cart ID
	 * @param  int|string  $storeView Store view ID or code (optional)
	 * @return array                  Array of shoppingCartPaymentMethodResponseEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartPayment/cart_payment.list.html
	 */
	public function callShoppingCartPaymentList(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartPaymentList($this->soapSessionId, $quoteId, $storeView);
	}
}