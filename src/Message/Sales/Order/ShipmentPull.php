<?php
namespace App\Message\Sales\Order;

/**
 * Message for order shipment pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentPull
{
	/**
	 * The shipmentIncrementId and orderIncrementId.
	 * 
	 * @var array
	 */
    private $data;

    /**
     * Track data.
     * 
     * @param int $orderId
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get track data.
     * 
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}