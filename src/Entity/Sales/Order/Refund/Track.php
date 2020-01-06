<?php

namespace App\Entity\Sales\Order\Refund;

use App\Entity\Sales\Order\Refund;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\Refund\TrackRepository")
 * @ORM\Table(name="sale_order_refund_track")
 */
class Track
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sales\Order\Refund", inversedBy="tracks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carrier_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $track_number;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Refund
    {
        return $this->parent;
    }

    public function setParent(?Refund $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrier_name;
    }

    public function setCarrierName(?string $carrier_name): self
    {
        $this->carrier_name = $carrier_name;

        return $this;
    }

    public function getTrackNumber(): ?string
    {
        return $this->track_number;
    }

    public function setTrackNumber(?string $track_number): self
    {
        $this->track_number = $track_number;

        return $this;
    }
}
