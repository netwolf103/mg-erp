<?php

namespace App\Entity\Sales\Order;

use App\Entity\SaleOrder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\RelatedRepository")
 * @ORM\Table(name="sale_order_related")
 */
class Related
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="related_orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $order_id;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $increment_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(?int $order_id): self
    {
        $this->order_id = $order_id;

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
}
