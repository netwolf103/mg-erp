<?php
namespace App\Traits;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Entity\SaleOrder;
use App\Entity\Config\Shipping\Method;

/**
 * Trait for rates.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
trait ShippingTrait
{

	/**
	 * Get rates object.
	 * 
	 * @param  ParameterBagInterface $parameter
	 * @return float
	 */
	public function getFreeShippingAmount(ParameterBagInterface $parameter, SaleOrder $order, Method $shippingMethod): float
	{
		$orderMinimumAmount = $parameter->get('free_shipping_minimum_amount');

		if ($order->getBaseSubtotal() > $orderMinimumAmount ) {
			return 0;
		}

		return $shippingMethod->getPrice();
	}

	/**
	 * Set order shipping method.
	 * 
	 * @param ParameterBagInterface $parameter
	 * @param SaleOrder             $order          [description]
	 * @param Method                $shippingMethod
	 */
	public function setShippingMethod(ParameterBagInterface $parameter, SaleOrder $order, Method $shippingMethod): self
	{
        $order->setShippingMethod($shippingMethod->getCode() .'_'. $shippingMethod->getCode());
        $order->setShippingDescription($shippingMethod->getTitle() .' - '. $shippingMethod->getName());

        $shippingPrice = $shippingMethod->getPrice();

        if ($this->isFreeShipping($shippingMethod)) {
        	$shippingPrice = $this->getFreeShippingAmount($parameter, $order, $shippingMethod);
        }

        $order->setShippingAmount($this->getRates($parameter)->convert($shippingPrice, $order->getOrderCurrencyCode()));
        $order->setBaseShippingAmount($shippingPrice);

        return $this;        
	}

	/**
	 * Is free shipping.
	 * 
	 * @param  Method  $shippingMethod
	 * @param  string  $code
	 * @return boolean
	 */
	public function isFreeShipping(Method $shippingMethod, string $code = 'freeshipping')
	{
		return $shippingMethod->getCode() == $code;
	}
}