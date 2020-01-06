<?php

namespace App\Entity\Product\Stock;

use App\Entity\Product;
use App\Entity\Product\Option\Dropdown;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\Stock\AlertRepository")
 * @ORM\Table(name="product_stock_alert")
 */
class Alert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;        

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product\Option\Dropdown", inversedBy="alert", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="alerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Dropdown
    {
        return $this->parent;
    }

    public function setParent(Dropdown $parent): self
    {
        $this->parent = $parent;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
}
