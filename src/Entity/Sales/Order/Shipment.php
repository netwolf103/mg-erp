<?php

namespace App\Entity\Sales\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Shipment\Item;
use App\Entity\Sales\Order\Shipment\Track;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\ShipmentRepository")
 * @ORM\Table(name="sale_order_shipment")
 */
class Shipment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $increment_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $store_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total_qty;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Shipment\Item", mappedBy="parent")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Shipment\Track", mappedBy="parent")
     */
    private $tracks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="shipments")
     */
    private $parent;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncrementId(): ?string
    {
        return $this->increment_id;
    }

    public function setIncrementId(?string $increment_id): self
    {
        $this->increment_id = $increment_id;

        return $this;
    }

    public function getStoreId(): ?int
    {
        return $this->store_id;
    }

    public function setStoreId(?int $store_id): self
    {
        $this->store_id = $store_id;

        return $this;
    }

    public function getTotalQty(): ?float
    {
        return $this->total_qty;
    }

    public function setTotalQty(?float $total_qty): self
    {
        $this->total_qty = $total_qty;

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

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setParent($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getParent() === $this) {
                $item->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
            $track->setParent($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
            // set the owning side to null (unless already changed)
            if ($track->getParent() === $this) {
                $track->setParent(null);
            }
        }

        return $this;
    }

    public function getParent(): ?SaleOrder
    {
        return $this->parent;
    }

    public function setParent(?SaleOrder $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
