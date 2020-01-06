<?php

namespace App\Entity\Sales\Order;

use App\Entity\Sales\Order\Refund;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\SaleOrder;
use App\Entity\Product;
use App\Entity\Product\Option\Dropdown;
use App\Entity\Product\Option\Field;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\ItemRepository")
 * @ORM\Table(name="sale_order_item")
 */
class Item
{
    /**
     * 产品类型
     *
     * 0: 虚拟产品
     * 1: 库存产品
     * 2: 采购产品
     * 3: 工厂产品
     */
    const ITEM_TYPE_VIRTUAL     = 0;
    const ITEM_TYPE_STOCK       = 1;
    const ITEM_TYPE_PURCHASE    = 2;
    const ITEM_TYPE_FACTORY     = 3;

    public static $_type;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quote_item_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $product_type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $product_options;

    /**
     * @ORM\Column(type="float")
     */
    private $weight = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_virtual = 0;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $free_shipping;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_qty_decimal;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $no_discount;

    /**
     * @ORM\Column(type="float")
     */
    private $qty_canceled = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $qty_invoiced;

    /**
     * @ORM\Column(type="float")
     */
    private $qty_ordered;

    /**
     * @ORM\Column(type="float")
     */
    private $qty_refunded = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $qty_shipped = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $base_price;

    /**
     * @ORM\Column(type="float")
     */
    private $original_price;

    /**
     * @ORM\Column(type="float")
     */
    private $tax_percent = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $tax_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_tax_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $tax_invoiced = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_tax_invoiced = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $discount_percent = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $discount_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_discount_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $discount_invoiced = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_discount_invoiced = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_refunded = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_amount_refunded = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $row_total;

    /**
     * @ORM\Column(type="float")
     */
    private $base_row_total;

    /**
     * @ORM\Column(type="float")
     */
    private $row_invoiced;

    /**
     * @ORM\Column(type="float")
     */
    private $base_row_invoiced;

    /**
     * @ORM\Column(type="float")
     */
    private $row_weight = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weee_tax_applied = 'a:0:{}';

    /**
     * @ORM\Column(type="float")
     */
    private $weee_tax_applied_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $weee_tax_applied_row_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_weee_tax_applied_amount = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $weee_tax_disposition = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $weee_tax_row_disposition = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_weee_tax_disposition = 0;

    /**
     * @ORM\Column(type="float")
     */
    private $base_weee_tax_row_disposition = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $itemType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="order_items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Refund", mappedBy="item")
     */
    private $refunds;

    public function __construct()
    {
        $this->refunds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuoteItemId(): ?int
    {
        return $this->quote_item_id;
    }

    public function setQuoteItemId(int $quote_item_id): self
    {
        $this->quote_item_id = $quote_item_id;

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

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->product_type;
    }

    public function setProductType(string $product_type): self
    {
        $this->product_type = $product_type;

        return $this;
    }

    public function getProductOptions(): ?string
    {
        return $this->product_options;
    }

    public function setProductOptions(?string $product_options): self
    {
        $this->product_options = $product_options;

        return $this;
    }

    public function getProductOptionSize()
    {
        $options = unserialize($this->product_options);
        if (!isset($options['options'])) {
            return [];
        }

        foreach ($options['options'] as $option) {
            if ($option['option_type'] == Dropdown::OPTION_TYPE) {
                return $option;
            }
        }

        return [];
    }

    public function getProductOptionEngravings()
    {
        $options = unserialize($this->product_options);
        if (!isset($options['options'])) {
            return [];
        }

        foreach ($options['options'] as $option) {
            if ($option['option_type'] == Field::OPTION_TYPE) {
                return $option;
            }
        }

        return [];       
    }

    public function productOptionsUnserialize()
    {
        return $this->product_options ? unserialize($this->product_options) : [];
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getIsVirtual(): ?bool
    {
        return $this->is_virtual;
    }

    public function setIsVirtual(bool $is_virtual): self
    {
        $this->is_virtual = $is_virtual;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFreeShipping(): ?bool
    {
        return $this->free_shipping;
    }

    public function setFreeShipping(?bool $free_shipping): self
    {
        $this->free_shipping = $free_shipping;

        return $this;
    }

    public function getIsQtyDecimal(): ?bool
    {
        return $this->is_qty_decimal;
    }

    public function setIsQtyDecimal(?bool $is_qty_decimal): self
    {
        $this->is_qty_decimal = $is_qty_decimal;

        return $this;
    }

    public function getNoDiscount(): ?bool
    {
        return $this->no_discount;
    }

    public function setNoDiscount(?bool $no_discount): self
    {
        $this->no_discount = $no_discount;

        return $this;
    }

    public function getQtyCanceled(): ?float
    {
        return $this->qty_canceled;
    }

    public function setQtyCanceled(float $qty_canceled): self
    {
        $this->qty_canceled += $qty_canceled;

        if ($this->qty_canceled < 0) {
            $this->qty_canceled = 0;
        }

        if ($this->qty_canceled > $this->getQtyOrdered()) {
            $this->qty_canceled = $this->getQtyOrdered();
        }

        return $this;
    }

    public function getQtyInvoiced(): ?float
    {
        return $this->qty_invoiced;
    }

    public function setQtyInvoiced(float $qty_invoiced): self
    {
        $this->qty_invoiced = $qty_invoiced;

        return $this;
    }

    public function getQtyOrdered(): ?float
    {
        return $this->qty_ordered;
    }

    public function setQtyOrdered(float $qty_ordered): self
    {
        $this->qty_ordered = $qty_ordered;

        return $this;
    }

    public function getQtyRefunded(): ?float
    {
        return $this->qty_refunded;
    }

    public function setQtyRefunded(float $qty_refunded): self
    {
        $this->qty_refunded += $qty_refunded;

        return $this;
    }

    public function getQtyShipped(): ?float
    {
        return $this->qty_shipped;
    }

    public function setQtyShipped(float $qty_shipped): self
    {
        $this->qty_shipped += $qty_shipped;

        if ($this->qty_shipped > $this->qty_ordered) {
            $this->qty_shipped = $this->qty_ordered;
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->base_price;
    }

    public function setBasePrice(float $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getOriginalPrice(): ?float
    {
        return $this->original_price;
    }

    public function setOriginalPrice(float $original_price): self
    {
        $this->original_price = $original_price;

        return $this;
    }

    public function getTaxPercent(): ?float
    {
        return $this->tax_percent;
    }

    public function setTaxPercent(float $tax_percent): self
    {
        $this->tax_percent = $tax_percent;

        return $this;
    }

    public function getTaxAmount(): ?float
    {
        return $this->tax_amount;
    }

    public function setTaxAmount(float $tax_amount): self
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getBaseTaxAmount(): ?float
    {
        return $this->base_tax_amount;
    }

    public function setBaseTaxAmount(float $base_tax_amount): self
    {
        $this->base_tax_amount = $base_tax_amount;

        return $this;
    }

    public function getTaxInvoiced(): ?float
    {
        return $this->tax_invoiced;
    }

    public function setTaxInvoiced(float $tax_invoiced): self
    {
        $this->tax_invoiced = $tax_invoiced;

        return $this;
    }

    public function getBaseTaxInvoiced(): ?float
    {
        return $this->base_tax_invoiced;
    }

    public function setBaseTaxInvoiced(float $base_tax_invoiced): self
    {
        $this->base_tax_invoiced = $base_tax_invoiced;

        return $this;
    }

    public function getDiscountPercent(): ?float
    {
        return $this->discount_percent;
    }

    public function setDiscountPercent(float $discount_percent): self
    {
        $this->discount_percent = $discount_percent;

        return $this;
    }

    public function getDiscountAmount(): ?float
    {
        return $this->discount_amount;
    }

    public function setDiscountAmount(float $discount_amount): self
    {
        $this->discount_amount = $discount_amount;

        return $this;
    }

    public function getBaseDiscountAmount(): ?float
    {
        return $this->base_discount_amount;
    }

    public function setBaseDiscountAmount(float $base_discount_amount): self
    {
        $this->base_discount_amount = $base_discount_amount;

        return $this;
    }

    public function getDiscountInvoiced(): ?float
    {
        return $this->discount_invoiced;
    }

    public function setDiscountInvoiced(float $discount_invoiced): self
    {
        $this->discount_invoiced = $discount_invoiced;

        return $this;
    }

    public function getBaseDiscountInvoiced(): ?float
    {
        return $this->base_discount_invoiced;
    }

    public function setBaseDiscountInvoiced(float $base_discount_invoiced): self
    {
        $this->base_discount_invoiced = $base_discount_invoiced;

        return $this;
    }

    public function getAmountRefunded(): ?float
    {
        return $this->amount_refunded;
    }

    public function setAmountRefunded(float $amount_refunded): self
    {
        $this->amount_refunded = $amount_refunded;

        return $this;
    }

    public function getBaseAmountRefunded(): ?float
    {
        return $this->base_amount_refunded;
    }

    public function setBaseAmountRefunded(float $base_amount_refunded): self
    {
        $this->base_amount_refunded = $base_amount_refunded;

        return $this;
    }

    public function getRowTotal(): ?float
    {
        return $this->row_total;
    }

    public function setRowTotal(float $row_total): self
    {
        $this->row_total = $row_total;

        return $this;
    }

    public function getBaseRowTotal(): ?float
    {
        return $this->base_row_total;
    }

    public function setBaseRowTotal(float $base_row_total): self
    {
        $this->base_row_total = $base_row_total;

        return $this;
    }

    public function getRowInvoiced(): ?float
    {
        return $this->row_invoiced;
    }

    public function setRowInvoiced(float $row_invoiced): self
    {
        $this->row_invoiced = $row_invoiced;

        return $this;
    }

    public function getBaseRowInvoiced(): ?float
    {
        return $this->base_row_invoiced;
    }

    public function setBaseRowInvoiced(float $base_row_invoiced): self
    {
        $this->base_row_invoiced = $base_row_invoiced;

        return $this;
    }

    public function getRowWeight(): ?float
    {
        return $this->row_weight;
    }

    public function setRowWeight(float $row_weight): self
    {
        $this->row_weight = $row_weight;

        return $this;
    }

    public function getWeeeTaxApplied(): ?string
    {
        return $this->weee_tax_applied;
    }

    public function setWeeeTaxApplied(string $weee_tax_applied): self
    {
        $this->weee_tax_applied = $weee_tax_applied;

        return $this;
    }

    public function getWeeeTaxAppliedAmount(): ?float
    {
        return $this->weee_tax_applied_amount;
    }

    public function setWeeeTaxAppliedAmount(float $weee_tax_applied_amount): self
    {
        $this->weee_tax_applied_amount = $weee_tax_applied_amount;

        return $this;
    }

    public function getWeeeTaxAppliedRowAmount(): ?float
    {
        return $this->weee_tax_applied_row_amount;
    }

    public function setWeeeTaxAppliedRowAmount(float $weee_tax_applied_row_amount): self
    {
        $this->weee_tax_applied_row_amount = $weee_tax_applied_row_amount;

        return $this;
    }

    public function getBaseWeeeTaxAppliedAmount(): ?float
    {
        return $this->base_weee_tax_applied_amount;
    }

    public function setBaseWeeeTaxAppliedAmount(float $base_weee_tax_applied_amount): self
    {
        $this->base_weee_tax_applied_amount = $base_weee_tax_applied_amount;

        return $this;
    }

    public function getWeeeTaxDisposition(): ?float
    {
        return $this->weee_tax_disposition;
    }

    public function setWeeeTaxDisposition(float $weee_tax_disposition): self
    {
        $this->weee_tax_disposition = $weee_tax_disposition;

        return $this;
    }

    public function getWeeeTaxRowDisposition(): ?float
    {
        return $this->weee_tax_row_disposition;
    }

    public function setWeeeTaxRowDisposition(float $weee_tax_row_disposition): self
    {
        $this->weee_tax_row_disposition = $weee_tax_row_disposition;

        return $this;
    }

    public function getBaseWeeeTaxDisposition(): ?float
    {
        return $this->base_weee_tax_disposition;
    }

    public function setBaseWeeeTaxDisposition(float $base_weee_tax_disposition): self
    {
        $this->base_weee_tax_disposition = $base_weee_tax_disposition;

        return $this;
    }

    public function getBaseWeeeTaxRowDisposition(): ?float
    {
        return $this->base_weee_tax_row_disposition;
    }

    public function setBaseWeeeTaxRowDisposition(float $base_weee_tax_row_disposition): self
    {
        $this->base_weee_tax_row_disposition = $base_weee_tax_row_disposition;

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

    public function getItemType(): ?string
    {
        return $this->itemType;
    }

    public function setItemType(string $itemType): self
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function canCancel(): bool
    {
        if (in_array($this->getParent()->getStatus(), [SaleOrder::ORDER_STATUS_CANCELED, SaleOrder::ORDER_STATUS_CLOSED, SaleOrder::ORDER_STATUS_COMPLETE])) {
            return false;
        }

        return ($this->getQtyOrdered() - $this->getQtyCanceled() - $this->getQtyRefunded()) > 0;
    }

    public function canRefund(): bool
    {
        if ($this->getQtyShipped() > 0) {
            return true;
        }

        return false;
    }

    public function canShip(): bool
    {
        return $this->getCanQtyShipped() > 0;
    }

    public function canEdit()
    {
        if ($this->getIsVirtual()) {
            return false;
        }

        if (in_array($this->getParent()->getStatus(), [SaleOrder::ORDER_STATUS_CANCELED, SaleOrder::ORDER_STATUS_CLOSED, SaleOrder::ORDER_STATUS_COMPLETE])) {
            return false;
        }

        return true;        
    }

    public function getCanQtyShipped()
    {
        return $this->getQtyOrdered() - $this->getQtyShipped() - $this->getQtyCanceled() - $this->getQtyRefunded();
    }

    /**
     * @return Collection|Refund[]
     */
    public function getRefunds(): Collection
    {
        return $this->refunds;
    }

    public function addRefund(Refund $refund): self
    {
        if (!$this->refunds->contains($refund)) {
            $this->refunds[] = $refund;
            $refund->setItem($this);
        }

        return $this;
    }

    public function removeRefund(Refund $refund): self
    {
        if ($this->refunds->contains($refund)) {
            $this->refunds->removeElement($refund);
            // set the owning side to null (unless already changed)
            if ($refund->getItem() === $this) {
                $refund->setItem(null);
            }
        }

        return $this;
    }

    public static function getTypeList(): array
    {
        if (is_null(static::$_type)) {
            static::$_type = array(
                static::ITEM_TYPE_VIRTUAL => 'Virtual Product',
                static::ITEM_TYPE_STOCK => 'Stock Product',
                static::ITEM_TYPE_PURCHASE => 'Purchase Product',
                static::ITEM_TYPE_FACTORY => 'Factory Product',
            );
        }
        return static::$_type;
    }     
}
