<?php
namespace App\Message\Catalog\Category\Product\Google;

/**
 * Message for push google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Push
{
	/**
	 * The id.
	 * 
	 * @var int
	 */
    private $id;

    /**
     * Id init.
     * 
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get id.
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}