<?php

namespace App\Entity\Sales\Order;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Invoice\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\InvoiceRepository")
 * @ORM\Table(name="sale_order_invoice")
 */
class Invoice
{
    /**
     * Invoice states
     */
    const STATE_OPEN       = 1;
    const STATE_PAID       = 2;
    const STATE_CANCELED   = 3;

    protected static $_states;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SaleOrder", inversedBy="Invoice", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $increment_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $store_id;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $global_currency_code;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $base_currency_code;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $store_currency_code;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $order_currency_code;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $store_to_base_rate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $store_to_order_rate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_to_global_rate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_to_order_rate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_subtotal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_grand_total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_discount_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shipping_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_shipping_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tax_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_tax_amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $grand_total;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Invoice\Item", mappedBy="parent")
     */
    private $Items;

    public function __construct()
    {
        $this->Items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?SaleOrder
    {
        return $this->parent;
    }

    public function setParent(SaleOrder $parent): self
    {
        $this->parent = $parent;

        return $this;
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

    public function getGlobalCurrencyCode(): ?string
    {
        return $this->global_currency_code;
    }

    public function setGlobalCurrencyCode(?string $global_currency_code): self
    {
        $this->global_currency_code = $global_currency_code;

        return $this;
    }

    public function getBaseCurrencyCode(): ?string
    {
        return $this->base_currency_code;
    }

    public function setBaseCurrencyCode(?string $base_currency_code): self
    {
        $this->base_currency_code = $base_currency_code;

        return $this;
    }

    public function getStoreCurrencyCode(): ?string
    {
        return $this->store_currency_code;
    }

    public function setStoreCurrencyCode(?string $store_currency_code): self
    {
        $this->store_currency_code = $store_currency_code;

        return $this;
    }

    public function getOrderCurrencyCode(): ?string
    {
        return $this->order_currency_code;
    }

    public function setOrderCurrencyCode(?string $order_currency_code): self
    {
        $this->order_currency_code = $order_currency_code;

        return $this;
    }

    public function getStoreToBaseRate(): ?float
    {
        return $this->store_to_base_rate;
    }

    public function setStoreToBaseRate(?float $store_to_base_rate): self
    {
        $this->store_to_base_rate = $store_to_base_rate;

        return $this;
    }

    public function getStoreToOrderRate(): ?float
    {
        return $this->store_to_order_rate;
    }

    public function setStoreToOrderRate(?float $store_to_order_rate): self
    {
        $this->store_to_order_rate = $store_to_order_rate;

        return $this;
    }

    public function getBaseToGlobalRate(): ?float
    {
        return $this->base_to_global_rate;
    }

    public function setBaseToGlobalRate(?float $base_to_global_rate): self
    {
        $this->base_to_global_rate = $base_to_global_rate;

        return $this;
    }

    public function getBaseToOrderRate(): ?float
    {
        return $this->base_to_order_rate;
    }

    public function setBaseToOrderRate(?float $base_to_order_rate): self
    {
        $this->base_to_order_rate = $base_to_order_rate;

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

    public function getBaseSubtotal(): ?float
    {
        return $this->base_subtotal;
    }

    public function setBaseSubtotal(?float $base_subtotal): self
    {
        $this->base_subtotal = $base_subtotal;

        return $this;
    }

    public function getBaseGrandTotal(): ?float
    {
        return $this->base_grand_total;
    }

    public function setBaseGrandTotal(?float $base_grand_total): self
    {
        $this->base_grand_total = $base_grand_total;

        return $this;
    }

    public function getDiscountAmount(): ?float
    {
        return $this->discount_amount;
    }

    public function setDiscountAmount(?float $discount_amount): self
    {
        $this->discount_amount = $discount_amount;

        return $this;
    }

    public function getBaseDiscountAmount(): ?float
    {
        return $this->base_discount_amount;
    }

    public function setBaseDiscountAmount(?float $base_discount_amount): self
    {
        $this->base_discount_amount = $base_discount_amount;

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

    public function getBaseShippingAmount(): ?float
    {
        return $this->base_shipping_amount;
    }

    public function setBaseShippingAmount(?float $base_shipping_amount): self
    {
        $this->base_shipping_amount = $base_shipping_amount;

        return $this;
    }

    public function getTaxAmount(): ?float
    {
        return $this->tax_amount;
    }

    public function setTaxAmount(?float $tax_amount): self
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getBaseTaxAmount(): ?float
    {
        return $this->base_tax_amount;
    }

    public function setBaseTaxAmount(?float $base_tax_amount): self
    {
        $this->base_tax_amount = $base_tax_amount;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
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
        return $this->Items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->Items->contains($item)) {
            $this->Items[] = $item;
            $item->setParent($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->Items->contains($item)) {
            $this->Items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getParent() === $this) {
                $item->setParent(null);
            }
        }

        return $this;
    }

    public static function getStates(): array
    {
        if (is_null(static::$_states)) {
            static::$_states = array(
                static::STATE_OPEN       => 'Pending',
                static::STATE_PAID       => 'Paid',
                static::STATE_CANCELED   => 'Canceled',
            );
        }
        return static::$_states;
    } 
}
