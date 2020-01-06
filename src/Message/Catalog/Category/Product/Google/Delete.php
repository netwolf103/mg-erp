<?php
namespace App\Message\Catalog\Category\Product\Google;

/**
 * Message for delete google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Delete
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