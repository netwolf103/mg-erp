<?php

namespace App\Api\Magento1x\Soap\Customer;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage customers.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CustomerSoap extends SoapAbstract
{
	/**
	 * Return customer groups.
	 * 
	 * @return array An array of customerGroupEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customer_group.html
	 */
	public function callCustomerGroupList()
	{
		return $this->soapClient->customerGroupList($this->soapSessionId);
	}

	/**
	 * Create a new customer.
	 * 
	 * @param  array  $data Array of customerCustomerEntityToCreate 
	 * @return int          ID of the created customer
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customer.create.html
	 */
	public function callCustomerCustomerCreate(array $data)
	{
		return $this->soapClient->customerCustomerCreate($this->soapSessionId, $data);
	}

	/**
	 * Delete the required customer.
	 * 
	 * @param  int    $customerId Customer ID
	 * @return boolean            True if the customer is deleted
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customer.delete.html
	 */
	public function callCustomerCustomerDelete(int $customerId)
	{
		return $this->soapClient->customerCustomerDelete($this->soapSessionId, $customerId);
	}

	/**
	 * Return information about the specified customer.
	 * 
	 * @param  int    $customerId ID of the required customer
	 * @return array              Array of customerCustomerEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customer.info.html
	 */
	public function callCustomerCustomerInfo(int $customerId)
	{
		return $this->soapClient->customerCustomerInfo($this->soapSessionId, $customerId);
	}

	/**
	 * Return the list of customers.
	 * 
	 * @param  array  $filters Array of filters by customer attributes (optional)
	 * @return array           Array of customerCustomerEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customer.list.html
	 */
	public function callCustomerCustomerList(array $filters = [])
	{
		return $this->soapClient->customerCustomerList($this->soapSessionId, $filters);
	}

	/**
	 * Update information about the required customer.
	 * 
	 * @param  int    $customerId Customer ID
	 * @param  array  $data       Array of customerCustomerEntityToCreate
	 * @return boolean            True if the customer is updated
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/customer/customer.update.html
	 */
	public function callCustomerCustomerUpdate(int $customerId, array $data)
	{
		return $this->soapClient->customerCustomerUpdate($this->soapSessionId, $customerId, $data);
	}
}