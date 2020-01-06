<?php

namespace App\Entity\Sales\Order;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\SaleOrder;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\PaymentRepository")
 * @ORM\Table(name="sale_order_payment")
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_ordered;

    /**
     * @ORM\Column(type="float")
     */
    private $shipping_amount;

    /**
     * @ORM\Column(type="float")
     */
    private $base_amount_ordered;

    /**
     * @ORM\Column(type="float")
     */
    private $base_shipping_amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $method;

    /**
     * @ORM\Column(type="integer")
     */
    private $payment_id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SaleOrder", inversedBy="payment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmountOrdered(): ?float
    {
        return $this->amount_ordered;
    }

    public function setAmountOrdered(float $amount_ordered): self
    {
        $this->amount_ordered = $amount_ordered;

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

    public function getBaseAmountOrdered(): ?float
    {
        return $this->base_amount_ordered;
    }

    public function setBaseAmountOrdered(float $base_amount_ordered): self
    {
        $this->base_amount_ordered = $base_amount_ordered;

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

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getPaymentId(): ?int
    {
        return $this->payment_id;
    }

    public function setPaymentId(int $payment_id): self
    {
        $this->payment_id = $payment_id;

        return $this;
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
}
