<?php

namespace App\Api\Yunexpress;

use App\Api\Yunexpress\ApiAbstract;

/**
 * WayBill class of YunExpress api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class WayBill extends ApiAbstract
{
	/**
	 * Query tracking number
	 * 
	 * @param  array  $trackingNumbers
	 * @return object
	 */	
	public function getTrackingNumber(array $trackingNumbers)
	{
		return $this->call(sprintf('/Waybill/GetTrackingNumber?CustomerOrderNumber=%s', join(',', $trackingNumbers)));
	}

	/**
	 * Query sender info
	 * 
	 * @param  string  $orderNumber
	 * @return object
	 */
	public function getSender(string $orderNumber)
	{
		return $this->call(sprintf('/Waybill/GetSender?OrderNumber=%s', $orderNumber));
	}

	/**
	 * Create orders (Up to 10 orders)
	 * 
	 * @param  string  $orders
	 * @return object
	 */
	public function createOrder(array $orders)
	{
		$this->postBodyPackage = false;
		return $this->call('/Waybill/CreateOrder', $orders);
	}

	/**
	 * Query a order.
	 * 
	 * @param  string  $orderNumOrTrackNum
	 * @return object
	 */
	public function getOrder(string $orderNumOrTrackNum)
	{
		return $this->call(sprintf('/Waybill/GetOrder?OrderNumber=%s', $orderNumOrTrackNum));
	}

	/**
	 * Delete a order.
	 * 
	 * @param  int  $orderType (单号类型：1-云途单号,2-客户订单号,3-跟踪号)
	 * @param  string  $orderNumber
	 * @return object
	 */
	public function deleteOrder(int $orderType, string $orderNumber)
	{
		$this->postBodyPackage = false;
		return $this->call('/Waybill/Delete', [
			'OrderType' => $orderType,
			'OrderNumber' => $orderNumber
		]);
	}

	/**
	 * Intercept a order.
	 * 
	 * @param  int  $orderType (单号类型：1-云途单号,2-客户订单号,3-跟踪号)
	 * @param  string  $orderNumber
	 * @param  string  $remark
	 * @return object
	 */
	public function interceptOrder(int $orderType, string $orderNumber, string $remark)
	{
		return $this->call('/Waybill/Intercept', [
			'OrderType' => $orderType,
			'OrderNumber' => $orderNumber,
			'Remark' => $remark,
		]);
	}

	/**
	 * Update order weight.
	 * 
	 * @param  string  $orderNumber
	 * @param  float  $weight
	 * @return object
	 */
	public function updateWeight(string $orderNumber, float $weight)
	{
		return $this->call('/Waybill/UpdateWeight', [
			'OrderNumber' => $orderNumber,
			'Weight' => $weight
		]);
	}

	/**
	 * Query final carrier.
	 * 
	 * @param  string  $orderNumOrTrackNum
	 * @param  float  $weight
	 * @return object
	 */
	public function getCarrier(string $orderNumOrTrackNum)
	{
		$this->postBodyPackage = false;
		return $this->call('/Waybill/GetCarrier', [
			$orderNumOrTrackNum
		]);
	}
}