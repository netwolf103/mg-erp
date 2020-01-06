<?php
namespace App\Message\Catalog\Category;

/**
 * Message for product pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductPull
{
	/**
	 * The product id.
	 * 
	 * @var int
	 */
    private $productId;

    /**
     * Product init.
     * 
     * @param int $productId
     */
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    /**
     * Get product id.
     * 
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }
}