<?php
namespace App\Message\Sales\Order;

/**
 * Message for order email push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class EmailPush
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
     * @param int    $orderId
     * @param string $email
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