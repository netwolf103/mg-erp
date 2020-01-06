<?php
namespace App\Message\Sales\Order;

/**
 * Message for order shipment push.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentPush
{
   /**
    * The track data
    * 
    * @var array
    */
    private $track;

    /**
     * Track data.
     * 
     * @param array $track
     */
    public function __construct(array $track)
    {
        $this->track = $track;
    }

    /**
     * Get track data.
     * 
     * @return array
     */
    public function getTrack(): array
    {
        return $this->track;
    }
}