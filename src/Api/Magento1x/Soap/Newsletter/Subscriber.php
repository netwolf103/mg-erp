<?php

namespace App\Api\Magento1x\Soap\Newsletter;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x newsletter subscriber soap client.
 * 
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SubscriberSoap extends SoapAbstract
{
	/**
	 * Return subscriber list.
	 * 
	 * @param  array  $filters Array of filters for the list of sales orders (optional)
	 * @return array
	 */
	public function callList(array $filters = [])
	{
		return $this->soapClient->subscriberList($this->soapSessionId, $filters);
	}
}