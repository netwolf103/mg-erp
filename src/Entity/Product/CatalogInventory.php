<?php

namespace App\Entity\Product;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\CatalogInventoryRepository")
 * @ORM\Table(name="product_catalog_inventory")
 */
class CatalogInventory
{
    /**
     * Stock status
     */
    const IN_STOCK      = 1;
    const OUT_OF_STOCK  = 0;

    protected static $_status;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="catalogInventory", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qty;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_in_stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

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

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(?int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getIsInStock(): ?bool
    {
        return $this->is_in_stock;
    }

    public function setIsInStock(?bool $is_in_stock): self
    {
        $this->is_in_stock = $is_in_stock;

        return $this;
    }

    public static function getStatusList(): array
    {
        if (is_null(static::$_status)) {
            static::$_status = array(
                static::IN_STOCK      => 'In Stock',
                static::OUT_OF_STOCK  => 'Out of Stock',
            );
        }
        return static::$_status;
    }
}
