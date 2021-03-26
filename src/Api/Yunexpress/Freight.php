<?php

namespace App\Api\Yunexpress;

use App\Api\Yunexpress\ApiAbstract;

/**
 * Freight class of YunExpress api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Freight extends ApiAbstract
{
	/**
	 * Query price
	 * 
	 * @param  string  $countryCode
	 * @param  float  $weight
	 * @param  int  $packageType (包裹类型，1-包裹，2-文件，3-防水袋)
	 * @param  int  $length
	 * @param  int  $width
	 * @param  int  $height
	 * @return object
	 */
	public function getPriceTrial(string $countryCode, float $weight, int $packageType = 1, int $length = 1, int $width = 1, int $height = 1)
	{
		return $this->call(sprintf('/Freight/GetPriceTrial?CountryCode=%s&Weight=%s&Length=%d&Width=%d&Height=%d&PackageType=%d', $countryCode, $weight, $length, $width, $height, $packageType));
	}

	/**
	 * Query shipping fee detail
	 * 
	 * @param  string  $wayBillNumber
	 * @return object
	 */
	public function getShippingFeeDetail(string $wayBillNumber)
	{
		return $this->call(sprintf('/Freight/GetShippingFeeDetail?WayBillNumber=%s', $wayBillNumber));
	}
}