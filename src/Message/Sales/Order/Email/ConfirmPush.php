<?php
namespace App\Message\Sales\Order\Email;

/**
 * Message for order send confirm email push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ConfirmPush
{
	/**
	 * The confirm id.
	 * 
	 * @var int
	 */
    private $confirmId;  

    /**
     * Order init.
     * 
     * @param int    $confirmId
     * @param string $email
     */
    public function __construct(int $confirmId)
    {
        $this->confirmId = $confirmId;
    }

    /**
     * Get order id.
     * 
     * @return int
     */
    public function getConfirmId(): int
    {
        return $this->confirmId;
    }
}