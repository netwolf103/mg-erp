<?php
namespace App\Message\Sales\Order\Address;

/**
 * Message for address geo pull.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GeoPull
{
	/**
	 * The address id.
	 * 
	 * @var int
	 */
    private $addressId;

    /**
     * Address init.
     * 
     * @param int $addressId
     */
    public function __construct(int $addressId)
    {
        $this->addressId = $addressId;
    }

    /**
     * Get address id.
     * 
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }
}