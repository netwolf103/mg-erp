<?php

namespace App\Api\Yunexpress;

use App\Api\Yunexpress\ApiAbstract;

/**
 * Label class of YunExpress api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Label extends ApiAbstract
{
	/**
	 * Label print
	 * 
	 * @param  array  $trackingNumbers
	 * @return object
	 */
	public function print(array $trackingNumbers)
	{
		$this->postBodyPackage = false;
		return $this->call('/Label/Print', $trackingNumbers);
	}
}