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
     * Init confirm id.
     * 
     * @param int    $confirmId
     */
    public function __construct(int $confirmId)
    {
        $this->confirmId = $confirmId;
    }

    /**
     * Get confirm id.
     * 
     * @return int
     */
    public function getConfirmId(): int
    {
        return $this->confirmId;
    }
}