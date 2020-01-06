<?php

namespace App\Api\Magento1x\Soap\Sales;

use App\Api\Magento1x\Soap\SoapAbstract;

/**
 * Magento 1.x sales order shippment soap client.
 * 
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderShipmentSoap extends SoapAbstract
{
	/**
	 * Return all shipment list.
	 * 
	 * @param  array  $filters Array of filters for the list of shipments
	 * @return array           Array of salesOrderShipmentEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.list.html
	 */
	public function callSalesOrderShipmentList(array $filters = [])
	{
		return $this->soapClient->salesOrderShipmentList($this->soapSessionId, $filters);
	}

	/**
	 * Return sales order shipment info.
	 * 
	 * @param  string $incrementId Order shipment increment ID
	 * @return array               Array of salesOrderShipmentEntity
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.info.html
	 */
	public function callSalesOrderShipmentInfo($incrementId)
	{
		return $this->soapClient->salesOrderShipmentInfo($this->soapSessionId, $incrementId);
	}

	/**
	 * Send email with shipment data to customer
	 * 
	 * @param  string $shipmentIncrementId
	 * @param  string $comment
	 * @return boolean
	 */
	public function callSalesOrderShipmentSendInfo($shipmentIncrementId, string $comment = '')
	{
		return $this->soapClient->salesOrderShipmentSendInfo($this->soapSessionId, $shipmentIncrementId, $comment);
	}

	/**
	 * Add a shipment comment.
	 * 
	 * @param  string $incrementId    Shipment increment ID
	 * @param  string $comment        Shipment comment (optional)
	 * @param  string $email          Send email flag (optional)
	 * @param  string $includeInEmail Include comment in email flag (optional)
	 * @return boolean|int
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.addComment.html
	 */
	public function callSalesOrderShipmentAddComment($incrementId, $comment = null, $email = null, $includeInEmail = null)
	{
		return $this->soapClient->salesOrderShipmentAddComment($this->soapSessionId, $incrementId, $comment, $email, $includeInEmail);
	}

	/**
	 * Add a tracking number to order.
	 * 
	 * @param  string $incrementId Shipment increment ID
	 * @param  string $carrierCode Carrier code (custom, ups, usps, dhl, fedex, or dhlint)
	 * @param  string $trackTitle  Tracking title
	 * @param  string $trackNumber Tracking number
	 * @return int
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.addTrack.html
	 */
	public function callSalesOrderShipmentAddTrack($incrementId, $carrierCode, $trackTitle, $trackNumber)
	{
		return $this->soapClient->salesOrderShipmentAddTrack($this->soapSessionId, $incrementId, $carrierCode, $trackTitle, $trackNumber);
	}

	/**
	 * Create a new shipment for an order.
	 * 
	 * @param  string $incrementId Order increment ID
	 * @param  array  $itemsQty    Array of orderItemIdQty (optional)
	 * @param  string $comment     Shipment comment (optional)
	 * @param  int $email          Send email flag (optional)
	 * @param  int $includeInEmail Include comment in email flag (optional)
	 * @return string
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.create.html
	 */
	public function callSalesOrderShipmentCreate($incrementId, $itemsQty = [], $comment = null, $email = 0, $includeInEmail = 0)
	{
		return $this->soapClient->salesOrderShipmentCreate($this->soapSessionId, $incrementId, $itemsQty, $comment, $email, $includeInEmail);
	}

	/**
	 * Return the list of allowed carriers for an order
	 * 
	 * @param  string $incrementId Order increment ID
	 * @return array
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.getCarriers.html
	 */
	public function callSalesOrderShipmentGetCarriers($incrementId)
	{
		return $this->soapClient->salesOrderShipmentGetCarriers($this->soapSessionId, $incrementId);
	}

	/**
	 * Remove a tracking number from the order shipment.
	 * 
	 * @param  string $incrementId Shipment increment ID
	 * @param  string $trackId     Track ID
	 * @return boolean|int
	 * @see https://devdocs.magento.com/guides/m1x/api/soap/sales/salesOrderShipment/sales_order_shipment.removeTrack.html
	 */
	public function callSalesOrderShipmentRemoveTrack($incrementId, $trackId)
	{
		return $this->soapClient->salesOrderShipmentRemoveTrack($this->soapSessionId, $incrementId, $trackId);
	}
}