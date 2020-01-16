<?php
namespace App\Message\Catalog\Category\Product;

/**
 * Message for product alert.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Alert
{
	/**
	 * The product option id.
	 * 
	 * @var int
	 */
    private $optionId;

    /**
     * Init product option id.
     * 
     * @param int $optionId
     */
    public function __construct(int $optionId)
    {
        $this->optionId = $optionId;
    }

    /**
     * Get product option id.
     * 
     * @return int
     */
    public function getOptionId(): int
    {
        return $this->optionId;
    }
}