<?php

namespace App\Api\Magento1x\Soap\Payment;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x payment transaction soap client.
 * Manage countries
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TransactionSoap extends SoapAbstract
{
	/**
	 * Return the list of countries from Magento.
	 *
	 * @return array An array of directoryCountryEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/directory/directory_country.list.html
	 */
	public function callSalesOrderPaymentTransactionInfo(string $incrementId)
	{
		return $this->soapClient->salesOrderPaymentTransactionInfo($this->soapSessionId, $incrementId);
	}
}