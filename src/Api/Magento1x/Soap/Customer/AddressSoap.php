<?php

namespace App\Api\Magento1x\Soap\Customer;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Create, retrieve, update, and delete address data for a required customer.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressSoap extends SoapAbstract
{
	/**
	 * Create a new address for the customer.
	 * 
	 * @param  int    $customerId Customer ID
	 * @param  array  $data       Array of customerAddressEntityCreate
	 * @return int                ID of the created customer address
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customerAddress/customer_address.create.html
	 */
	public function callCustomerAddressCreate(int $customerId, array $data)
	{
		return $this->soapClient->customerAddressCreate($this->soapSessionId, $customerId, $data);
	}

	/**
	 * Delete the required customer address.
	 * 
	 * @param  int    $addressId Address ID
	 * @return boolean           True if the customer address is deleted
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customerAddress/customer_address.delete.html
	 */
	public function callCustomerAddressDelete(int $addressId)
	{
		return $this->soapClient->customerAddressDelete($this->soapSessionId, $addressId);
	}

	/**
	 * Return information about the required customer address.
	 * 
	 * @param  int    $addressId Address ID
	 * @return array             Array of customerAddressEntityItem
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customerAddress/customer_address.info.html
	 */
	public function callCustomerAddressInfo(int $addressId)
	{
		return $this->soapClient->customerAddressInfo($this->soapSessionId, $addressId);
	}

	/**
	 * Return the list of customer addresses.
	 * 
	 * @param  int    $customerId Customer ID
	 * @return array              Array of customerAddressEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customerAddress/customer_address.list.html
	 */
	public function callCustomerAddressList(int $customerId)
	{
		return $this->soapClient->customerAddressList($this->soapSessionId, $customerId);
	}

	/**
	 * Update address data of the required customer.
	 * 
	 * @param  int    $addressId Address ID
	 * @param  array  $data      Array of customerAddressEntityCreate
	 * @return boolean           True if the customer address is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customerAddress/customer_address.update.html
	 */
	public function callCustomerAddressUpdate(int $addressId, array $data)
	{
		return $this->soapClient->customerAddressUpdate($this->soapSessionId, $addressId, $data);
	}
}