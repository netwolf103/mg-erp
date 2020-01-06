<?php

namespace App\Api\Magento1x\Soap\Sales;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x sale order soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderSoap extends SoapAbstract
{
	/**
	 * Return all order list.
	 * 
	 * @param  array  $filters Array of filters for the list of sales orders (optional)
	 * @return array           Array of salesOrderEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrder/sales_order.list.html
	 */
	public function callOrderList(array $filters = [])
	{
		return $this->soapClient->salesOrderList($this->soapSessionId, $filters);
	}

	/**
	 * Return order info.
	 *
	 * @param  string Order increment ID
	 * @return arra   Array of salesOrderEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrder/sales_order.info.html
	 */
	public function callOrderInfo($incrementId)
	{
		return $this->soapClient->salesOrderInfo($this->soapSessionId, $incrementId);
	}

	/**
	 * Add comment to order.
	 * 
	 * @param  string $incrementId Order increment ID
	 * @param  string $status      Order status (pending, processing, etc.)
	 * @param  string $comment     Order comment (optional)
	 * @param  string $notify      Notification flag (optional)
	 * @return boolean|int         True (1) if the comment is added to the order
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrder/sales_order.addComment.html
	 */
	public function callAddComment($incrementId, string $status, string $comment, $notify = null)
	{
		return $this->soapClient->salesOrderAddComment($this->soapSessionId, $incrementId, $status, $comment, $notify);
	}

	/**
	 * Order cancel.
	 *
	 * @param  string $incrementId Order increment ID
	 * @return boolean             True if the order is canceled
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrder/sales_order.cancel.html
	 */
	public function callCancel($incrementId)
	{
		return $this->soapClient->salesOrderCancel($this->soapSessionId, $incrementId);
	}

	/**
	 * Order hold.
	 *
	 * @param  string $incrementId Order increment ID
	 * @return boolean             True (1) if the order is placed on hold
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrder/sales_order.hold.html
	 */	
	public function callHold($incrementId)
	{
		return $this->soapClient->salesOrderHold($this->soapSessionId, $incrementId);
	}

	/**
	 * Order unhold.
	 *
	 * @param  string $incrementId Order increment ID
	 * @return boolean             True (1) if the order is unheld
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrder/sales_order.unhold.html
	 */
	public function callUnhold($incrementId)
	{
		return $this->soapClient->salesOrderUnhold($this->soapSessionId, $incrementId);
	}

	/**
	 * Update order data info.
	 * 
	 * @param  string $incrementId
	 * @param  array  $orderData
	 * @return boolean
	 */
	public function callOrderUpdate(string $incrementId, array $orderData)
	{
		return $this->soapClient->salesOrderUpdateData($this->soapSessionId, $incrementId, $orderData);
	}

	/**
	 * Send confirm email.
	 * 
	 * @param  string $incrementId
	 * @return bool
	 */
	public function callSendConfirmEmail(string $incrementId)
	{
		return $this->soapClient->salesOrderEmailConfirmemail($this->soapSessionId, $incrementId);
	}
}