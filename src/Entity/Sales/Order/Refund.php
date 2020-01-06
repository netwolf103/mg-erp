<?php

namespace App\Entity\Sales\Order;

use App\Entity\Sales\Order\Item;
use App\Entity\Sales\Order\Refund\Track;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\RefundRepository")
 * @ORM\Table(name="sale_order_refund")
 */
class Refund
{
    /**
     * Refund status
     */
    const STATUS_N = 0;
    const STATUS_Y = 1;
    const STATUS_R = 2;

    protected static $_status;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qty_ordered;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qty_refunded;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $row_total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $refund_amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $refunded_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Refund\Track", mappedBy="parent")
     */
    private $tracks;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sales\Order\Item", inversedBy="refunds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;   

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQtyOrdered(): ?int
    {
        return $this->qty_ordered;
    }

    public function setQtyOrdered(?int $qty_ordered): self
    {
        $this->qty_ordered = $qty_ordered;

        return $this;
    }

    public function getQtyRefunded(): ?int
    {
        return $this->qty_refunded;
    }

    public function setQtyRefunded(?int $qty_refunded): self
    {
        $this->qty_refunded = $qty_refunded;

        return $this;
    }

    public function getRowTotal(): ?float
    {
        return $this->row_total;
    }

    public function setRowTotal(?float $row_total): self
    {
        $this->row_total = $row_total;

        return $this;
    }

    public function getRefundAmount(): ?float
    {
        return $this->refund_amount;
    }

    public function setRefundAmount(?float $refund_amount): self
    {
        $this->refund_amount = $refund_amount;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getRefundedAt(): ?\DateTimeInterface
    {
        return $this->refunded_at;
    }

    public function setRefundedAt(?\DateTimeInterface $refunded_at): self
    {
        $this->refunded_at = $refunded_at;

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

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public static function getStatusLabel(): array
    {
        if (is_null(static::$_status)) {
            static::$_status = array(
                static::STATUS_N => 'Has not refunded',
                static::STATUS_Y => 'Has refunded',
                static::STATUS_R => 'Refuse to refund',
            );
        }
        return static::$_status;
    }

    public function canAgree()
    {
        return $this->getStatus() == static::STATUS_N;
    } 

    public function canRefuse()
    {
        return $this->getStatus() == static::STATUS_N;
    }             
}
