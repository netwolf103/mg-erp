<?php

namespace App\Entity\Product\Option;

use App\Entity\Product\Option;
use App\Entity\Product\Stock\Alert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\Option\DropdownRepository")
 * @ORM\Table(name="product_option_dropdown")
 */
class Dropdown
{
    const OPTION_TYPE = 'drop_down';
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $price_type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort_order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product\Option", inversedBy="optionValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    private $inventory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product\Option\Dropdown", inversedBy="childOptions")
     */
    private $parent_option;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Option\Dropdown", mappedBy="parent_option")
     */
    private $childOptions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inventory_low;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product\Stock\Alert", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $alert;

    public function __construct()
    {
        $this->childOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPriceType(): ?string
    {
        return $this->price_type;
    }

    public function setPriceType(string $price_type): self
    {
        $this->price_type = $price_type;

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

    public function getSortOrder(): ?int
    {
        return $this->sort_order;
    }

    public function setSortOrder(int $sort_order): self
    {
        $this->sort_order = $sort_order;

        return $this;
    }

    public function getParent(): ?Option
    {
        return $this->parent;
    }

    public function setParent(?Option $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getInventory(): ?int
    {
        return $this->inventory;
    }

    public function setInventory(int $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function getParentOption(): ?self
    {
        return $this->parent_option;
    }

    public function setParentOption(?self $parent_option): self
    {
        $this->parent_option = $parent_option;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildOptions(): Collection
    {
        return $this->childOptions;
    }

    public function addChildOption(self $childOption): self
    {
        if (!$this->childOptions->contains($childOption)) {
            $this->childOptions[] = $childOption;
            $childOption->setParentOption($this);
        }

        return $this;
    }

    public function removeChildOption(self $childOption): self
    {
        if ($this->childOptions->contains($childOption)) {
            $this->childOptions->removeElement($childOption);
            // set the owning side to null (unless already changed)
            if ($childOption->getParentOption() === $this) {
                $childOption->setParentOption(null);
            }
        }

        return $this;
    }

    public function getInventoryLow(): ?int
    {
        return $this->inventory_low;
    }

    public function setInventoryLow(?int $inventory_low): self
    {
        $this->inventory_low = $inventory_low;

        return $this;
    }

    public function getAlert(): ?Alert
    {
        return $this->alert;
    }

    public function setAlert(Alert $alert): self
    {
        $this->alert = $alert;

        // set the owning side of the relation if necessary
        if ($this !== $alert->getParent()) {
            $alert->setParent($this);
        }

        return $this;
    }
}
