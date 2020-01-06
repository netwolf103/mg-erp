<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Product\Media;
use App\Entity\Product\Option;
use App\Entity\Product\Option\Dropdown;
use App\Entity\Product\Option\Field;
use App\Entity\Sales\Order\Item;
use App\Entity\Product\Stock\Alert;
use App\Entity\Product\CatalogInventory;
use App\Entity\Product\Google;
use App\Entity\Product\Purchase\Order;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * Product status
     */
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED  = 1;

    protected static $_status;

    /**
     * 产品线
     *
     * 0: 虚拟产品
     * 1: 采购产品
     * 2: 工厂产品
     */
    const LINE_VIRTUAL      = 0;
    const LINE_PURCHASE     = 1;
    const LINE_FACTORY      = 2;
    const LINE_UNDEFINED    = 11;

    protected static $_lineTypes;

    /**
     * 虚拟产品标识
     */
    const TYPE_VIRTUAL_FLAG = 'virtual';    

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $type_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_path;

    /**
     * @ORM\Column(type="smallint")
     */
    private $visibility;

    /**
     * @ORM\Column(type="smallint")
     */
    private $has_options;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     */
    private $special_price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Media", mappedBy="product", orphanRemoval=true)
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Option", mappedBy="product", orphanRemoval=true)
     */
    private $options;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $purchase_price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Supplier", inversedBy="products")
     */
    private $supplier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $purchase_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_url;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Item", mappedBy="product")
     */
    private $order_items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Stock\Alert", mappedBy="product")
     */
    private $alerts;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product\Google", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $google;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product\CatalogInventory", mappedBy="product", cascade={"persist", "remove"})
     */
    private $catalogInventory;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_sample;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Purchase\Order", mappedBy="parent")
     */
    private $purchase_orders;                

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->order_items = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->purchase_orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTypeId(): ?string
    {
        return $this->type_id;
    }

    public function setTypeId(string $type_id): self
    {
        $this->type_id = $type_id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getUrlPath(): ?string
    {
        return $this->url_path;
    }

    public function setUrlPath(string $url_path): self
    {
        $this->url_path = $url_path;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getHasOptions(): ?int
    {
        return $this->has_options;
    }

    public function setHasOptions(int $has_options): self
    {
        $this->has_options = $has_options;

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

    public function getSpecialPrice(): ?float
    {
        return $this->special_price;
    }

    public function setSpecialPrice(float $special_price): self
    {
        $this->special_price = $special_price;

        return $this;
    }

    public function getFinalPrice(): ?float
    {
        return $this->special_price ? $this->special_price : $this->price;
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

    /**
     * @return Collection|media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }   

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setProduct($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getProduct() === $this) {
                $medium->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductOption[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setProduct($this);
        }

        return $this;
    }

    public function getOptionIds()
    {
        $ids = [];

        foreach ($this->getOptions() as $_option) {
           $ids[] = $_option->getId();
        }

        return $ids;
    }

    public function getOptionSizes()
    {
        return $this->getOptionDropDown() ? $this->getOptionDropDown()->getOptionValuesDropdown() : [];
    }

    public function getOptionEngravings()
    {
        return $this->getOptionField();
    }

    public function getOptionDropDown()
    {
        $options = $this->getOptionByType(Dropdown::OPTION_TYPE);

        return $options->first();        
    }

    public function getOptionField()
    {
        $options = $this->getOptionByType(Field::OPTION_TYPE);

        return $options->first();        
    }   

    public function getOptionByType(string $type)
    {
        $options = $this->getOptions()->filter(function(Option $option) use ($type) {
            return $option->getType() == $type;
        });

        return $options;        
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            // set the owning side to null (unless already changed)
            if ($option->getProduct() === $this) {
                $option->setProduct(null);
            }
        }

        return $this;
    }

    public function getPurchasePrice(): ?float
    {
        return $this->purchase_price;
    }

    public function setPurchasePrice(?float $purchase_price): self
    {
        $this->purchase_price = $purchase_price;

        return $this;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    public function setInventory(?int $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getPurchaseUrl(): ?string
    {
        return $this->purchase_url;
    }

    public function setPurchaseUrl(?string $purchase_url): self
    {
        $this->purchase_url = $purchase_url;

        return $this;
    }

    public function getProductUrl(): ?string
    {
        return $this->product_url;
    }

    public function setProductUrl(?string $product_url): self
    {
        $this->product_url = $product_url;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getOrderItems(): Collection
    {
        return $this->order_items;
    }

    public function addOrderItem(Item $orderItem): self
    {
        if (!$this->order_items->contains($orderItem)) {
            $this->order_items[] = $orderItem;
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(Item $orderItem): self
    {
        if ($this->order_items->contains($orderItem)) {
            $this->order_items->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

     /**
     * @return Collection|Alerts[]
     */
    public function getAlert(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setProduct($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->contains($alert)) {
            $this->alerts->removeElement($alert);
            // set the owning side to null (unless already changed)
            if ($alert->getProduct() === $this) {
                $alert->setProduct(null);
            }
        }

        return $this;
    }   

    public static function getStatusList(): array
    {
        if (is_null(static::$_status)) {
            static::$_status = array(
                static::STATUS_DISABLED => 'Disabled',
                static::STATUS_ENABLED  => 'Enabled',
            );
        }
        return static::$_status;
    }

    public function getGoogle(): ?Google
    {
        return $this->google;
    }

    public function setGoogle(?Google $google): self
    {
        $this->google = $google;

        // set (or unset) the owning side of the relation if necessary
        $newParent = $google === null ? null : $this;
        if ($newParent !== $google->getParent()) {
            $google->setParent($newParent);
        }

        return $this;
    }

    public function getCatalogInventory(): ?CatalogInventory
    {
        return $this->catalogInventory;
    }

    public function setCatalogInventory(CatalogInventory $catalogInventory): self
    {
        $this->catalogInventory = $catalogInventory;

        // set the owning side of the relation if necessary
        if ($this !== $catalogInventory->getProduct()) {
            $catalogInventory->setProduct($this);
        }

        return $this;
    }

    public function getHasSample(): ?bool
    {
        return $this->has_sample;
    }

    public function setHasSample(?bool $has_sample): self
    {
        $this->has_sample = $has_sample;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getPurchaseOrders(): Collection
    {
        return $this->purchase_orders;
    }

    public function addPurchaseOrder(Order $purchaseOrder): self
    {
        if (!$this->purchase_orders->contains($purchaseOrder)) {
            $this->purchase_orders[] = $purchaseOrder;
            $purchaseOrder->setParent($this);
        }

        return $this;
    }

    public function removePurchaseOrder(Order $purchaseOrder): self
    {
        if ($this->purchase_orders->contains($purchaseOrder)) {
            $this->purchase_orders->removeElement($purchaseOrder);
            // set the owning side to null (unless already changed)
            if ($purchaseOrder->getParent() === $this) {
                $purchaseOrder->setParent(null);
            }
        }

        return $this;
    }
    
    public function isVirtual(): bool
    {
        return $this->getTypeId() === static::TYPE_VIRTUAL_FLAG;
    }

    public function isPurchase()
    {
        return $this->getLineType() == static::LINE_PURCHASE;
    }

    public function isFactory()
    {
        return $this->getLineType() == static::LINE_FACTORY;
    }

     public function isUndefined()
    {
        return $this->getLineType() == static::LINE_UNDEFINED;
    }   

    public function getLineType()
    {
        if ($this->isVirtual()) {
            return static::LINE_VIRTUAL;
        }

        if ($this->getPurchaseUrl()) {
            return static::LINE_PURCHASE;
        }

        if ($this->getSupplier()) {
            return static::LINE_FACTORY;
        }

        return static::LINE_UNDEFINED;
    } 

    public static function getLineTypeList(): array
    {
        if (is_null(static::$_lineTypes)) {
            static::$_lineTypes = array(
                static::LINE_VIRTUAL    => 'Virtual Product',
                static::LINE_PURCHASE   => 'Purchase Product',
                static::LINE_FACTORY    => 'Factory Product',
                static::LINE_UNDEFINED  => 'Undefined Product',
            );
        }
        return static::$_lineTypes;
    }                   
}
