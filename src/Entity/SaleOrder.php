<?php

namespace App\Entity;

use App\Entity\Sales\Order\ConfirmEmailHistory;
use App\Entity\Sales\Order\Expedited;
use App\Entity\Sales\Order\Invoice;
use App\Entity\Sales\Order\Payment\Transaction;
use App\Entity\Sales\Order\Related;
use App\Entity\Sales\Order\Shipping\History;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Sales\Order\Address;
use App\Entity\Sales\Order\Comment;
use App\Entity\Sales\Order\Payment;
use App\Entity\Sales\Order\Item;
use App\Entity\Sales\Order\Shipment;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\OrderRepository")
 */
class SaleOrder
{
    /**
     * 订单类型
     *
     * 0: 虚拟单
     * 1: 库存单
     * 2: 采购单
     * 3: 工厂单
     * 4: 组合单
     */
    const ORDER_TYPE_VIRTUAL     = 0;
    const ORDER_TYPE_STOCK       = 1;
    const ORDER_TYPE_PURCHASE    = 2;
    const ORDER_TYPE_FACTORY     = 3;
    const ORDER_TYPE_COMBINATION = 4;

    protected static $_types;

    /**
     * 订单状态
     */
    const ORDER_STATUS_CANCELED = 'canceled';
    const ORDER_STATUS_CLOSED = 'closed';
    const ORDER_STATUS_COMPLETE = 'complete';
    const ORDER_STATUS_FRAUD = 'fraud';
    const ORDER_STATUS_HOLDED = 'holded';
    const ORDER_STATUS_PAYMENT_REVIEW = 'payment_review';
    const ORDER_STATUS_PAYPAL_CANCELED_REVERSAL = 'paypal_canceled_reversal';
    const ORDER_STATUS_PAYPAL_REVERSED = 'paypal_reversed';
    const ORDER_STATUS_PENDING = 'pending';
    const ORDER_STATUS_PENDING_PAYMENT = 'pending_payment';
    const ORDER_STATUS_PENDING_PAYPAL = 'pending_paypal';
    const ORDER_STATUS_PROCESSING = 'processing';  
    const ORDER_STATUS_PART_SHIPPED = 'part_shipped';
    const ORDER_STATUS_UNDELIVERED = 'undelivered';
    const ORDER_STATUS_WAIT_CUSTOMER_CONTACT = 'wait_customer_contact';
    const ORDER_STATUS_RESHIPPING = 'reshipping';

    protected static $_status;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $increment_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="float")
     */
    private $tax_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $shipping_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $discount_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float")
     */
    private $grand_total;

    /**
     * @ORM\Column(type="smallint")
     */
    private $total_qty_ordered;

    /**
     * @ORM\Column(type="float")
     */
    private $base_tax_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $base_shipping_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $base_discount_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $base_subtotal;

    /**
     * @ORM\Column(type="float")
     */
    private $base_grand_total;

    /**
     * @ORM\Column(type="float")
     */
    private $store_to_base_rate;

    /**
     * @ORM\Column(type="float")
     */
    private $store_to_order_rate;

    /**
     * @ORM\Column(type="float")
     */
    private $base_to_global_rate;

    /**
     * @ORM\Column(type="float")
     */
    private $base_to_order_rate;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $store_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $remote_ip;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $global_currency_code;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $base_currency_code;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $store_currency_code;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $order_currency_code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $shipping_method;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shipping_description;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $customer_email;

    /**
     * @ORM\Column(type="integer")
     */
    private $quote_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_virtual;

    /**
     * @ORM\Column(type="integer")
     */
    private $customer_group_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $customer_note_notify;

    /**
     * @ORM\Column(type="boolean")
     */
    private $customer_is_guest;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sales\Order\Payment", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $payment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Item", mappedBy="parent")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Address", mappedBy="parent")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $orderType; 

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Comment", mappedBy="parent")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Shipment", mappedBy="parent")
     */
    private $shipments;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sales\Order\Invoice", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $Invoice;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sales\Order\Expedited", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $expedited;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Shipping\History", mappedBy="parent")
     */
    private $shippingHistorys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Related", mappedBy="parent")
     */
    private $related_orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Payment\Transaction", mappedBy="parent_order")
     */
    private $payment_transactions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tracking_number_to_platform_synced;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\ConfirmEmailHistory", mappedBy="parent")
     */
    private $confirm_email_historys;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->address = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->shipments = new ArrayCollection();
        $this->shippingHistorys = new ArrayCollection();
        $this->related_orders = new ArrayCollection();
        $this->payment_transactions = new ArrayCollection();
        $this->confirm_email_historys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncrementId(): ?string
    {
        return $this->increment_id;
    }

    public function setIncrementId(string $increment_id): self
    {
        $this->increment_id = $increment_id;

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

    public function getTaxAmount(): ?float
    {
        return $this->tax_amount;
    }

    public function setTaxAmount(float $tax_amount): self
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getShippingAmount(): ?float
    {
        return $this->shipping_amount;
    }

    public function setShippingAmount(float $shipping_amount): self
    {
        $this->shipping_amount = $shipping_amount;

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

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getGrandTotal(): ?float
    {
        return $this->grand_total;
    }

    public function setGrandTotal(float $grand_total): self
    {
        $this->grand_total = $grand_total;

        return $this;
    }

    public function getTotalQtyOrdered(): ?int
    {
        return $this->total_qty_ordered;
    }

    public function setTotalQtyOrdered(int $total_qty_ordered): self
    {
        $this->total_qty_ordered = $total_qty_ordered;

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

    public function getBaseShippingAmount(): ?float
    {
        return $this->base_shipping_amount;
    }

    public function setBaseShippingAmount(float $base_shipping_amount): self
    {
        $this->base_shipping_amount = $base_shipping_amount;

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

    public function getBaseSubtotal(): ?float
    {
        return $this->base_subtotal;
    }

    public function setBaseSubtotal(float $base_subtotal): self
    {
        $this->base_subtotal = $base_subtotal;

        return $this;
    }

    public function getBaseGrandTotal(): ?float
    {
        return $this->base_grand_total;
    }

    public function setBaseGrandTotal(float $base_grand_total): self
    {
        $this->base_grand_total = $base_grand_total;

        return $this;
    }

    public function getStoreToBaseRate(): ?float
    {
        return $this->store_to_base_rate;
    }

    public function setStoreToBaseRate(float $store_to_base_rate): self
    {
        $this->store_to_base_rate = $store_to_base_rate;

        return $this;
    }

    public function getStoreToOrderRate(): ?float
    {
        return $this->store_to_order_rate;
    }

    public function setStoreToOrderRate(float $store_to_order_rate): self
    {
        $this->store_to_order_rate = $store_to_order_rate;

        return $this;
    }

    public function getBaseToGlobalRate(): ?float
    {
        return $this->base_to_global_rate;
    }

    public function setBaseToGlobalRate(float $base_to_global_rate): self
    {
        $this->base_to_global_rate = $base_to_global_rate;

        return $this;
    }

    public function getBaseToOrderRate(): ?float
    {
        return $this->base_to_order_rate;
    }

    public function setBaseToOrderRate(float $base_to_order_rate): self
    {
        $this->base_to_order_rate = $base_to_order_rate;

        return $this;
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

    public function getStoreName(): ?string
    {
        return $this->store_name;
    }

    public function setStoreName(string $store_name): self
    {
        $this->store_name = $store_name;

        return $this;
    }

    public function getRemoteIp(): ?string
    {
        return $this->remote_ip;
    }

    public function setRemoteIp(string $remote_ip): self
    {
        $this->remote_ip = $remote_ip;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getGlobalCurrencyCode(): ?string
    {
        return $this->global_currency_code;
    }

    public function setGlobalCurrencyCode(string $global_currency_code): self
    {
        $this->global_currency_code = $global_currency_code;

        return $this;
    }

    public function getBaseCurrencyCode(): ?string
    {
        return $this->base_currency_code;
    }

    public function setBaseCurrencyCode(string $base_currency_code): self
    {
        $this->base_currency_code = $base_currency_code;

        return $this;
    }

    public function getStoreCurrencyCode(): ?string
    {
        return $this->store_currency_code;
    }

    public function setStoreCurrencyCode(string $store_currency_code): self
    {
        $this->store_currency_code = $store_currency_code;

        return $this;
    }

    public function getOrderCurrencyCode(): ?string
    {
        return $this->order_currency_code;
    }

    public function setOrderCurrencyCode(string $order_currency_code): self
    {
        $this->order_currency_code = $order_currency_code;

        return $this;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shipping_method;
    }

    public function setShippingMethod(string $shipping_method): self
    {
        $this->shipping_method = $shipping_method;

        return $this;
    }

    public function getShippingDescription(): ?string
    {
        return $this->shipping_description;
    }

    public function setShippingDescription(string $shipping_description): self
    {
        $this->shipping_description = $shipping_description;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getQuoteId(): ?int
    {
        return $this->quote_id;
    }

    public function setQuoteId(int $quote_id): self
    {
        $this->quote_id = $quote_id;

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

    public function getCustomerGroupId(): ?int
    {
        return $this->customer_group_id;
    }

    public function setCustomerGroupId(int $customer_group_id): self
    {
        $this->customer_group_id = $customer_group_id;

        return $this;
    }

    public function getCustomerNoteNotify(): ?int
    {
        return $this->customer_note_notify;
    }

    public function setCustomerNoteNotify(int $customer_note_notify): self
    {
        $this->customer_note_notify = $customer_note_notify;

        return $this;
    }

    public function getCustomerIsGuest(): ?bool
    {
        return $this->customer_is_guest;
    }

    public function setCustomerIsGuest(bool $customer_is_guest): self
    {
        $this->customer_is_guest = $customer_is_guest;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        // set the owning side of the relation if necessary
        if ($this !== $payment->getParent()) {
            $payment->setParent($this);
        }

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
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setParent($this);
        }

        return $this;
    }

    public function getShippingAddress()
    {
        $address = $this->getAddress()->filter(function(Address $address) {
            return $address->getAddressType() == Address::ADDRESS_SHIPPING;
        });

        return $address->first();
    }

    public function getBillingAddress()
    {
        $address = $this->getAddress()->filter(function(Address $address) {
            return $address->getAddressType() == Address::ADDRESS_BILLING;
        });

        return $address->first();
    }    

    public function removeAddress(Address $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getParent() === $this) {
                $address->setParent(null);
            }
        }

        return $this;
    }

    public function getOrderType(): ?string
    {
        return $this->orderType;
    }

    public function setOrderType(string $orderType): self
    {
        $this->orderType = $orderType;

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

    /**
     * @return Collection|Shipment[]
     */
    public function getShipments(): Collection
    {
        return $this->shipments;
    }

    public function addShipment(Shipment $shipment): self
    {
        if (!$this->shipments->contains($shipment)) {
            $this->shipments[] = $shipment;
            $shipment->setParent($this);
        }

        return $this;
    }

    public function removeShipment(Shipment $shipment): self
    {
        if ($this->shipments->contains($shipment)) {
            $this->shipments->removeElement($shipment);
            // set the owning side to null (unless already changed)
            if ($shipment->getParent() === $this) {
                $shipment->setParent(null);
            }
        }

        return $this;
    }

    public function canShip(): bool
    {
        if ($this->getIsVirtual()) {
            return false;
        }

        if (in_array($this->getStatus(), [static::ORDER_STATUS_CANCELED, static::ORDER_STATUS_CLOSED, static::ORDER_STATUS_HOLDED, static::ORDER_STATUS_PAYMENT_REVIEW, static::ORDER_STATUS_PENDING])) {
            return false;
        }

        if ($this->getQtyNotShipped()) {
            return true;
        }

        return false;
    }

    public function canHold(): bool
    {
        if (in_array($this->getStatus(), [static::ORDER_STATUS_PROCESSING])) {
            return true;
        }

        return false;
    }

    public function canUnHold(): bool
    {
        if (in_array($this->getStatus(), [static::ORDER_STATUS_HOLDED])) {
            return true;
        }

        return false;
    }

    public function canEditAddress(): bool
    {
        if (in_array($this->getStatus(), [static::ORDER_STATUS_CANCELED, static::ORDER_STATUS_CLOSED, static::ORDER_STATUS_COMPLETE])) {
            return false;
        }

        return true;
    }

    public function canEditShippingMethod(): bool
    {
        if (in_array($this->getStatus(), [static::ORDER_STATUS_CANCELED, static::ORDER_STATUS_CLOSED, static::ORDER_STATUS_COMPLETE])) {
            return false;
        }

        return true;
    }

    public function canAddItem()
    {
         if (in_array($this->getStatus(), [static::ORDER_STATUS_CANCELED, static::ORDER_STATUS_CLOSED, static::ORDER_STATUS_COMPLETE])) {
            return false;
        }

        return true;       
    } 

    public function canEditPlatformShipmentStatus()
    {
        if ($this->getTrackingNumberToPlatformSynced()) {
            return false;
        }

         if (!in_array($this->getStatus(), [static::ORDER_STATUS_COMPLETE])) {
            return false;
        }

        return true;
    }       

    public function getQtyNotShipped(): int
    {
        $notShippedQty = 0;
        foreach ($this->getItems() as $item) {
            if ($item->getIsVirtual()) {
                continue;
            }

            $notShippedQty += $item->getCanQtyShipped();
        }

        return ($notShippedQty > 0) ? $notShippedQty : 0;        
    }

    public function getInvoice(): ?Invoice
    {
        return $this->Invoice;
    }

    public function setInvoice(Invoice $Invoice): self
    {
        $this->Invoice = $Invoice;

        // set the owning side of the relation if necessary
        if ($this !== $Invoice->getParent()) {
            $Invoice->setParent($this);
        }

        return $this;
    }

    public static function getOrderTypeList(): array
    {
        if (is_null(static::$_types)) {
            static::$_types = array(
                static::ORDER_TYPE_VIRTUAL => 'Virtual Order',
                static::ORDER_TYPE_STOCK => 'Stock Order',
                static::ORDER_TYPE_PURCHASE => 'Purchase Order',
                static::ORDER_TYPE_FACTORY => 'Factory Order',
                static::ORDER_TYPE_COMBINATION => 'Factory Combination',
            );
        }
        return static::$_types;
    } 

    public static function getStatusList(): array
    {
        if (is_null(static::$_status)) {
            static::$_status = array(
                static::ORDER_STATUS_CANCELED => 'Canceled',
                static::ORDER_STATUS_CLOSED => 'Closed',
                static::ORDER_STATUS_COMPLETE => 'Complete',
                static::ORDER_STATUS_FRAUD => 'Suspected Fraud',
                static::ORDER_STATUS_HOLDED => 'On Hold',
                static::ORDER_STATUS_PAYMENT_REVIEW => 'Payment Review',
                static::ORDER_STATUS_PAYPAL_CANCELED_REVERSAL => 'PayPal Canceled Reversal',
                static::ORDER_STATUS_PAYPAL_REVERSED => 'PayPal Reversed',
                static::ORDER_STATUS_PENDING => 'Pending',
                static::ORDER_STATUS_PENDING_PAYMENT => 'Pending Payment',
                static::ORDER_STATUS_PENDING_PAYPAL => 'Pending PayPal',
                static::ORDER_STATUS_PROCESSING => 'Processing',
                static::ORDER_STATUS_PART_SHIPPED => 'Part Shipped',
                static::ORDER_STATUS_UNDELIVERED => 'Undelivered',
                static::ORDER_STATUS_WAIT_CUSTOMER_CONTACT => 'Waiting For Customer Contact Us',
                static::ORDER_STATUS_RESHIPPING => 'Re-Shipping',
            );
        }
        return static::$_status;
    }

    public function getExpedited(): ?Expedited
    {
        return $this->expedited;
    }

    public function setExpedited(Expedited $expedited): self
    {
        $this->expedited = $expedited;

        // set the owning side of the relation if necessary
        if ($this !== $expedited->getParent()) {
            $expedited->setParent($this);
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getShippingHistorys(): Collection
    {
        return $this->shippingHistorys;
    }

    public function addShippingHistory(History $shippingHistory): self
    {
        if (!$this->shippingHistorys->contains($shippingHistory)) {
            $this->shippingHistorys[] = $shippingHistory;
            $shippingHistory->setParent($this);
        }

        return $this;
    }

    public function removeShippingHistory(History $shippingHistory): self
    {
        if ($this->shippingHistorys->contains($shippingHistory)) {
            $this->shippingHistorys->removeElement($shippingHistory);
            // set the owning side to null (unless already changed)
            if ($shippingHistory->getParent() === $this) {
                $shippingHistory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Related[]
     */
    public function getRelatedOrders(): Collection
    {
        return $this->related_orders;
    }

    public function addRelatedOrder(Related $relatedOrder): self
    {
        if (!$this->related_orders->contains($relatedOrder)) {
            $this->related_orders[] = $relatedOrder;
            $relatedOrder->setParent($this);
        }

        return $this;
    }

    public function removeRelatedOrder(Related $relatedOrder): self
    {
        if ($this->related_orders->contains($relatedOrder)) {
            $this->related_orders->removeElement($relatedOrder);
            // set the owning side to null (unless already changed)
            if ($relatedOrder->getParent() === $this) {
                $relatedOrder->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getPaymentTransactions(): Collection
    {
        return $this->payment_transactions;
    }

    public function addPaymentTransaction(Transaction $paymentTransaction): self
    {
        if (!$this->payment_transactions->contains($paymentTransaction)) {
            $this->payment_transactions[] = $paymentTransaction;
            $paymentTransaction->setParentOrder($this);
        }

        return $this;
    }

    public function removePaymentTransaction(Transaction $paymentTransaction): self
    {
        if ($this->payment_transactions->contains($paymentTransaction)) {
            $this->payment_transactions->removeElement($paymentTransaction);
            // set the owning side to null (unless already changed)
            if ($paymentTransaction->getParentOrder() === $this) {
                $paymentTransaction->setParentOrder(null);
            }
        }

        return $this;
    }

    public function getTrackingNumberToPlatformSynced(): ?bool
    {
        return $this->tracking_number_to_platform_synced;
    }

    public function setTrackingNumberToPlatformSynced(?bool $tracking_number_to_platform_synced): self
    {
        $this->tracking_number_to_platform_synced = $tracking_number_to_platform_synced;

        return $this;
    }

    /**
     * @return Collection|ConfirmEmailHistory[]
     */
    public function getConfirmEmailHistorys(): Collection
    {
        return $this->confirm_email_historys;
    }

    public function addConfirmEmailHistory(ConfirmEmailHistory $confirmEmailHistory): self
    {
        if (!$this->confirm_email_historys->contains($confirmEmailHistory)) {
            $this->confirm_email_historys[] = $confirmEmailHistory;
            $confirmEmailHistory->setParent($this);
        }

        return $this;
    }

    public function removeConfirmEmailHistory(ConfirmEmailHistory $confirmEmailHistory): self
    {
        if ($this->confirm_email_historys->contains($confirmEmailHistory)) {
            $this->confirm_email_historys->removeElement($confirmEmailHistory);
            // set the owning side to null (unless already changed)
            if ($confirmEmailHistory->getParent() === $this) {
                $confirmEmailHistory->setParent(null);
            }
        }

        return $this;
    }     
}
