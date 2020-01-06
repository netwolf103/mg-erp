<?php

namespace App\Entity\Sales\Order\Invoice;

use App\Entity\Sales\Order\Invoice;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\Invoice\ItemRepository")
 * @ORM\Table(name="sale_order_invoice_item")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sales\Order\Invoice", inversedBy="Items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $weee_tax_applied;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qty;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $row_total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_row_total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_weee_tax_applied_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_weee_tax_applied_row_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weee_tax_applied_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weee_tax_applied_row_amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weee_tax_disposition;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weee_tax_row_disposition;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_weee_tax_disposition;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base_weee_tax_row_disposition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Invoice
    {
        return $this->parent;
    }

    public function setParent(?Invoice $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getWeeeTaxApplied(): ?string
    {
        return $this->weee_tax_applied;
    }

    public function setWeeeTaxApplied(?string $weee_tax_applied): self
    {
        $this->weee_tax_applied = $weee_tax_applied;

        return $this;
    }

    public function getQty(): ?float
    {
        return $this->qty;
    }

    public function setQty(?float $qty): self
    {
        $this->qty = $qty;

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

    public function getRowTotal(): ?float
    {
        return $this->row_total;
    }

    public function setRowTotal(?float $row_total): self
    {
        $this->row_total = $row_total;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->base_price;
    }

    public function setBasePrice(?float $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }

    public function getBaseRowTotal(): ?float
    {
        return $this->base_row_total;
    }

    public function setBaseRowTotal(?float $base_row_total): self
    {
        $this->base_row_total = $base_row_total;

        return $this;
    }

    public function getBaseWeeeTaxAppliedAmount(): ?float
    {
        return $this->base_weee_tax_applied_amount;
    }

    public function setBaseWeeeTaxAppliedAmount(?float $base_weee_tax_applied_amount): self
    {
        $this->base_weee_tax_applied_amount = $base_weee_tax_applied_amount;

        return $this;
    }

    public function getBaseWeeeTaxAppliedRowAmount(): ?float
    {
        return $this->base_weee_tax_applied_row_amount;
    }

    public function setBaseWeeeTaxAppliedRowAmount(?float $base_weee_tax_applied_row_amount): self
    {
        $this->base_weee_tax_applied_row_amount = $base_weee_tax_applied_row_amount;

        return $this;
    }

    public function getWeeeTaxAppliedAmount(): ?float
    {
        return $this->weee_tax_applied_amount;
    }

    public function setWeeeTaxAppliedAmount(?float $weee_tax_applied_amount): self
    {
        $this->weee_tax_applied_amount = $weee_tax_applied_amount;

        return $this;
    }

    public function getWeeeTaxAppliedRowAmount(): ?float
    {
        return $this->weee_tax_applied_row_amount;
    }

    public function setWeeeTaxAppliedRowAmount(?float $weee_tax_applied_row_amount): self
    {
        $this->weee_tax_applied_row_amount = $weee_tax_applied_row_amount;

        return $this;
    }

    public function getWeeeTaxDisposition(): ?float
    {
        return $this->weee_tax_disposition;
    }

    public function setWeeeTaxDisposition(?float $weee_tax_disposition): self
    {
        $this->weee_tax_disposition = $weee_tax_disposition;

        return $this;
    }

    public function getWeeeTaxRowDisposition(): ?float
    {
        return $this->weee_tax_row_disposition;
    }

    public function setWeeeTaxRowDisposition(?float $weee_tax_row_disposition): self
    {
        $this->weee_tax_row_disposition = $weee_tax_row_disposition;

        return $this;
    }

    public function getBaseWeeeTaxDisposition(): ?float
    {
        return $this->base_weee_tax_disposition;
    }

    public function setBaseWeeeTaxDisposition(?float $base_weee_tax_disposition): self
    {
        $this->base_weee_tax_disposition = $base_weee_tax_disposition;

        return $this;
    }

    public function getBaseWeeeTaxRowDisposition(): ?float
    {
        return $this->base_weee_tax_row_disposition;
    }

    public function setBaseWeeeTaxRowDisposition(?float $base_weee_tax_row_disposition): self
    {
        $this->base_weee_tax_row_disposition = $base_weee_tax_row_disposition;

        return $this;
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
}
