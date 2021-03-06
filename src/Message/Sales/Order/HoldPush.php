<?php
namespace App\Message\Sales\Order;

/**
 * Message for order hold push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class HoldPush
{
	/**
	 * The order id.
	 * 
	 * @var int
	 */
    private $orderId;

    /**
     * Order init.
     * 
     * @param int $orderId
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Get order id.
     * 
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }
}