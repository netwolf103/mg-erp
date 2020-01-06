<?php

namespace App\Api\Magento1x\Soap\Checkout;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Add and remove coupon codes for a shopping cart.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CouponSoap extends SoapAbstract
{
	/**
	 * Add a coupon code for a shopping cart (quote).
	 * 
	 * @param  int    $quoteId    Shopping cart ID
	 * @param  string $couponCode Coupon code
	 * @param  int|string $storeView  Store view ID or code (optional)
	 * @return boolean            True if the coupon code is added
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartCoupon/cart_coupon.add.html
	 */
	public function callShoppingCartCouponAdd(int $quoteId, string $couponCode, $storeView = null)
	{
		return $this->soapClient->shoppingCartCouponAdd($this->soapSessionId, $quoteId, $couponCode, $storeView);
	}

	/**
	 * Remove a coupon code from a shopping cart (quote).
	 * 
	 * @param  int    $quoteId   Shopping cart ID
	 * @param  int|string $storeView Store view ID or code (optional)
	 * @return boolean           True if the coupon code is removed
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/checkout/cartCoupon/cart_coupon.remove.html
	 */
	public function callShoppingCartCouponRemove(int $quoteId, $storeView = null)
	{
		return $this->soapClient->shoppingCartCouponRemove($this->soapSessionId, $quoteId, $storeView);
	}
}