<?php

namespace App\Api\Magento1x\Soap\Checkout;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Add customer information and addresses into a shopping cart.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CustomerSoap extends SoapAbstract
{
	/**
	 * Set the customer addresses in the shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  array  $data      Array of shoppingCartCustomerAddressEntity
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return boolean           True if the address is set
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartCustomer/cart_customer.addresses.html
	 */
	public function callShoppingCartCustomerAddresses(int $quoteId, array $data, $storeView = null)
	{
		return $this->soapClient->shoppingCartCustomerAddresses($this->soapSessionId, $quoteId, $data, $storeView);
	}

	/**
	 * Add information about the customer to a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  array  $data      Array of shoppingCartCustomerEntity
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return boolean           True if information is added
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartCustomer/cart_customer.set.html
	 */
	public function callShoppingCartCreate(int $quoteId, array $data, $storeView = null)
	{
		return $this->soapClient->shoppingCartCreate($this->soapSessionId, $quoteId, $data, $storeView);
	}
}