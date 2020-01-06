<?php

namespace App\Api\Magento1x\Soap\Sales;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x sale order invoice soap client.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderInvoiceSoap extends SoapAbstract
{
	/**
	 * Add a new comment to the order invoice.
	 * 
	 * @param  string  $incrementId    Invoice increment ID
	 * @param  [type]  $comment        Invoice comment (optional)
	 * @param  integer $email          Send invoice on email flag (optional)
	 * @param  integer $includeComment Include comment in email flag (optional)
	 * @return boolean
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderInvoice/sales_order_invoice.addComment.html
	 */
	public function callSalesOrderInvoiceAddComment($incrementId, $comment = null, $email = 0, $includeComment = 0)
	{
		return $this->soapClient->salesOrderInvoiceAddComment($this->soapSessionId, $incrementId, $comment, $email, $includeComment);
	}

	/**
	 * Capture the required invoice.
	 * 
	 * @param  string $incrementId Invoice increment ID
	 * @return boolean|int
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderInvoice/sales_order_invoice.capture.html
	 */
	public function callSalesOrderInvoiceCapture($incrementId)
	{
		return $this->soapClient->salesOrderInvoiceCapture($this->soapSessionId, $incrementId);
	}

	/**
	 * Cancel the required invoice.
	 * 
	 * @param  string $incrementId Invoice increment ID
	 * @return boolean
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderInvoice/sales_order_invoice.cancel.html
	 */
	public function callSalesOrderInvoiceCancel($incrementId)
	{
		return $this->soapClient->salesOrderInvoiceCancel($this->soapSessionId, $incrementId);
	}	

	/**
	 * Create a new invoice for an order.
	 * 
	 * @param  string $incrementId    Order increment ID
	 * @param  array $itemsQty        Array of orderItemIdQty (quantity of items to invoice)
	 * @param  string $comment        Invoice comment (optional)
	 * @param  string $email          Send invoice on email (optional)
	 * @param  string $includeComment Include comments in email (optional)
	 * @return string                 ID of the created invoice
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderInvoice/sales_order_invoice.create.html
	 */
	public function callSalesOrderInvoiceCreate($incrementId, $itemsQty, $comment = null, $email = null, $includeComment = null)
	{
		return $this->soapClient->salesOrderInvoiceCreate($this->soapSessionId, $incrementId, $itemsQty, $comment, $email, $includeComment);
	}

	/**
	 * Return information about the required invoice.
	 *
	 * @param  string $incrementId Invoice increment ID
	 * @return array               Array of salesOrderInvoiceEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderInvoice/sales_order_invoice.info.html
	 */
	public function callSalesOrderInvoiceInfo($incrementId)
	{
		return $this->soapClient->salesOrderInvoiceInfo($this->soapSessionId, $incrementId);
	}

	/**
	 * Return the list of order invoices.
	 * 
	 * @param  array  $filters Array of filters for the list of invoices (optional)
	 * @return arrray          Array of salesOrderInvoiceEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderInvoice/sales_order_invoice.list.html
	 */
	public function callSalesOrderInvoiceList($filters = [])
	{
		return $this->soapClient->salesOrderInvoiceList($this->soapSessionId, $filters);
	}
}