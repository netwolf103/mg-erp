<?php

namespace App\Entity\Product\Purchase;

use App\Entity\Product;
use App\Entity\Product\Purchase\Order\Item;
use App\Entity\Product\Purchase\Order\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\Purchase\OrderRepository")
 * @ORM\Table(name="product_purchase_order")
 */
class Order
{
    /**
     * 订单状态
     */
    const STATUS_NEW        = 'new';
    const STATUS_COMPLETE   = 'complete';

    protected static $_status;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="purchase_orders")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_qty_ordered;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shipping_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $grand_total;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $source_order_number;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Purchase\Order\Item", mappedBy="parent", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Purchase\Order\Comment", mappedBy="parent")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $comments;    

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Product
    {
        return $this->parent;
    }

    public function setParent(?Product $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalQtyOrdered(): ?int
    {
        return $this->total_qty_ordered;
    }

    public function setTotalQtyOrdered(?int $total_qty_ordered): self
    {
        $this->total_qty_ordered = $total_qty_ordered;

        return $this;
    }

    public function getShippingAmount(): ?float
    {
        return $this->shipping_amount;
    }

    public function setShippingAmount(?float $shipping_amount): self
    {
        $this->shipping_amount = $shipping_amount;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(?float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getGrandTotal(): ?float
    {
        return $this->grand_total;
    }

    public function setGrandTotal(?float $grand_total): self
    {
        $this->grand_total = $grand_total;

        return $this;
    }

    public function getSourceOrderNumber(): ?string
    {
        return $this->source_order_number;
    }

    public function setSourceOrderNumber(?string $source_order_number): self
    {
        $this->source_order_number = $source_order_number;

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
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getParent() === $this) {
                $comment->setParent(null);
            }
        }

        return $this;
    }

    public static function getStatusList(): array
    {
        if (is_null(static::$_status)) {
            static::$_status = array(
                static::STATUS_NEW      => 'New',
                static::STATUS_COMPLETE => 'Complete',
            );
        }
        return static::$_status;
    }

    public function isNew(): bool
    {
        return $this->getStatus() == static::STATUS_NEW;
    }

    public function isComplete(): bool
    {
        return $this->getStatus() == static::STATUS_COMPLETE;
    }               
}
