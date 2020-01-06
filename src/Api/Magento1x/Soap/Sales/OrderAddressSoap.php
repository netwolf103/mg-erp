<?php

namespace App\Api\Magento1x\Soap\Sales;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x sales order address soap client.
 * 
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderAddressSoap extends SoapAbstract
{
	/**
	 * Return order address list.
	 * 
	 * @param  string $incrementId
	 * @return array
	 */
	public function callSalesOrderAddressList(string $incrementId)
	{
		return $this->soapClient->salesOrderAddressList($this->soapSessionId, $incrementId);
	}

	/**
	 * Update order address info.
	 * 
	 * @param  string $incrementId
	 * @param  string $addressType
	 * @param  array  $addressData
	 * @return boolean
	 */
	public function callSalesOrderAddressUpdate(string $incrementId, string $addressType, array $addressData)
	{
		return $this->soapClient->salesOrderAddressUpdate($this->soapSessionId, $incrementId, $addressType, $addressData);
	}
}