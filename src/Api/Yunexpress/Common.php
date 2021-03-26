<?php

namespace App\Api\Yunexpress;

use App\Api\Yunexpress\ApiAbstract;

/**
 * Common class of YunExpress api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Common extends ApiAbstract
{
	/**
	 * Query country shortcode
	 * 
	 * @return object
	 */	
	public function getCountry()
	{
		return $this->call('/Common/GetCountry');
	}

	/**
	 * Query shipping methods
	 *
	 * @param  string  $countryCode
	 * @return object
	 */
	public function getShippingMethods(string $countryCode = '')
	{
		$uri = '/Common/GetShippingMethods' . ($countryCode ? sprintf('?CountryCode=%s', $countryCode) : '');

		return $this->call($uri);
	}

	/**
	 * Query goods type
	 * 
	 * @return object
	 */	
	public function getGoodsType()
	{
		return $this->call('/Common/GetGoodsType');
	}

	/**
	 * Register a user
	 * 
	 * @return object
	 */		
	public function Register(
		string $userName,
		string $passWord,
		string $contact,
		string $mobile,
		string $telephone,
		string $name,
		string $email,
		string $address,
		int $platForm
	)
	{
		return $this->call('/Common/Register', [
			'UserName' => $userName,
			'PassWord' => $passWord,
			'Contact' => $contact,
			'Mobile' => $mobile,
			'Telephone' => $telephone,
			'Name' => $name,
			'Email' => $email,
			'Address' => $address,
			'PlatForm' => $platForm,
		]);
	}
}