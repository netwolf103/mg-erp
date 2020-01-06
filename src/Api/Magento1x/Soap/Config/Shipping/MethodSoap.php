<?php

namespace App\Api\Magento1x\Soap\Config\Shipping;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x get shipping methods soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MethodSoap extends SoapAbstract
{
	/**
	 * Return shipping method list.
	 * 
	 * @param  bool|boolean $all
	 * @return array
	 */
	public function callConfigShippingMethodList(bool $all = false)
	{
		return $this->soapClient->configShippingMethodList($this->soapSessionId, $all);
	}
}