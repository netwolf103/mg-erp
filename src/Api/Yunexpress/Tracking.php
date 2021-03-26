<?php

namespace App\Api\Yunexpress;

use App\Api\Yunexpress\ApiAbstract;

/**
 * Tracking class of YunExpress api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Tracking extends ApiAbstract
{
	/**
	 * Query a track info
	 * 
	 * @param  string  $orderNumOrTrackNum
	 * @return object
	 */	
	public function getTrackInfo(string $orderNumOrTrackNum)
	{
		return $this->call(sprintf('/Tracking/GetTrackInfo?OrderNumber=%s', $orderNumOrTrackNum));
	}
}