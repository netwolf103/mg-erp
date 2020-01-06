<?php

namespace App\Api\Magento1x\Soap\Directory;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x product soap client.
 * Manage countries
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class DirectorySoap extends SoapAbstract
{
	/**
	 * Return the list of countries from Magento.
	 *
	 * @return array An array of directoryCountryEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/directory/directory_country.list.html
	 */
	public function callDirectoryCountryList()
	{
		return $this->soapClient->directoryCountryList($this->soapSessionId);
	}

	/**
	 * Return the list of regions in the specified country.
	 * 
	 * @param  string $countryCode Country code in ISO2 or ISO3
	 * @return array               An array of directoryRegionEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/directory/directory_region.list.html
	 */
	public function callDirectoryRegionList(string $countryCode)
	{
		return $this->soapClient->directoryRegionList($this->soapSessionId, $countryCode);
	}
}