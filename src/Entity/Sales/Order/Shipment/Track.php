<?php

namespace App\Entity\Sales\Order\Shipment;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Sales\Order\Shipment;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\Shipment\TrackRepository")
 * @ORM\Table(name="sale_order_shipment_track")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Sales\Order\Shipment", inversedBy="tracks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $carrier_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $track_number;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Shipment
    {
        return $this->parent;
    }

    public function setParent(?Shipment $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCarrierCode(): ?string
    {
        return $this->carrier_code;
    }

    public function setCarrierCode(?string $carrier_code): self
    {
        $this->carrier_code = $carrier_code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
