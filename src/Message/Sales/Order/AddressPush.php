<?php
namespace App\Message\Sales\Order;

/**
 * Message for order address push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressPush
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