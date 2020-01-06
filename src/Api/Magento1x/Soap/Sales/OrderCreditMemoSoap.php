<?php

namespace App\Api\Magento1x\Soap\Sales;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x sale order soap client.
 * Operate with credit memos for orders.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderCreditMemoSoap extends SoapAbstract
{
	/**
	 * Add a new comment to an existing credit memo.
	 * 
	 * @param  string      $incrementId    Credit memo increment ID
	 * @param  string      $comment        Comment text (optional)
	 * @param  int|integer $notifyCustomer Notify customer by email flag (optional)
	 * @param  int|integer $includeComment Include comment text into the email notification (optional)
	 * @return boolean|int                 True (1) if the comment is added to the credit memo
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderCreditMemo/sales_order_creditmemo.addComment.html
	 */
	public function callSalesOrderCreditmemoAddComment(string $incrementId, string $comment = '', int $notifyCustomer = 0, int $includeComment = 0)
	{
		return $this->soapClient->salesOrderCreditmemoAddComment($this->soapSessionId, $incrementId, $comment, $notifyCustomer, $includeComment);
	}

	/**
	 * Cancel an existing credit memo.
	 * 
	 * @param  string $incrementId Credit memo increment ID
	 * @return string              Result of canceling the credit memo
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderCreditMemo/sales_order_creditmemo.cancel.html
	 */
	public function callSalesOrderCreditmemoCancel(string $incrementId)
	{
		return $this->soapClient->salesOrderCreditmemoCancel($this->soapSessionId, $incrementId);
	}

	/**
	 * Create a new credit memo for the invoiced order.
	 * 
	 * @param  string      $incrementId               Order increment ID
	 * @param  array       $data                      Array of salesOrderCreditmemoData (optional)
	 * @param  string      $comment                   Comment text (optional)
	 * @param  int|integer $notifyCustomer            Notify customer by email flag (optional)
	 * @param  int|integer $includeComment            Include comment text into an email notification (optional)
	 * @param  string|null $refundToStoreCreditAmount Payment amount to be refunded to the customer store credit (optional)
	 * @return string                                 Created credit memo increment ID
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderCreditMemo/sales_order_creditmemo.create.html
	 */
	public function callSalesOrderCreditmemoCreate(string $incrementId, array $data = [], string $comment = '', int $notifyCustomer = 0, int $includeComment = 0, string $refundToStoreCreditAmount = null)
	{
		return $this->soapClient->salesOrderCreditmemoCreate($this->soapSessionId, $incrementId, $data, $comment, $notifyCustomer, $includeComment, $refundToStoreCreditAmount);
	}

	/**
	 * Return full information about the specified credit memo.
	 * 
	 * @param  string $incrementId Credit memo increment ID
	 * @return array               Array of salesOrderCreditmemoEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderCreditMemo/sales_order_creditmemo.info.html
	 */
	public function callSalesOrderCreditmemoInfo(string $incrementId)
	{
		return $this->soapClient->salesOrderCreditmemoInfo($this->soapSessionId, $incrementId);
	}

	/**
	 * Return  the list of credit memos by filters.
	 *
	 * @param  array  $filters Filters (optional)
	 * @return array           Array of salesOrderCreditmemoEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderCreditMemo/sales_order_creditmemo.list.html
	 */
	public function callSalesOrderCreditmemoList(array $filters = [])
	{
		return $this->soapClient->salesOrderCreditmemoList($this->soapSessionId, $filters);
	}
}