<?php
namespace App\Message;

/**
 * Message for customer pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CustomerPull
{
	/**
	 * The user id.
	 * 
	 * @var int
	 */
    private $userId;

    /**
     * User init.
     * 
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get user id.
     * 
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}