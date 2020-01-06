<?php

namespace App\Entity\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Product;
use App\Entity\Product\Option\Field;
use App\Entity\Product\Option\Dropdown;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\OptionRepository")
 * @ORM\Table(name="product_option")
 */
class Option
{
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
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort_order;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_require;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Option\Dropdown", mappedBy="parent", orphanRemoval=true)
     */
    private $optionValuesDropdown;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product\Option\Field", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $optionValueField;

    public function __construct()
    {
        $this->optionValuesDropdown = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getIsRequire(): ?bool
    {
        return $this->is_require;
    }

    public function setIsRequire(bool $is_require): self
    {
        $this->is_require = $is_require;

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

    /**
     * @return Collection|Dropdown[]
     */
    public function getOptionValuesDropdown(): Collection
    {
        return $this->optionValuesDropdown;
    }

    public function addOptionValueDropdown(Dropdown $optionValueDropdown): self
    {
        if (!$this->optionValuesDropdown->contains($optionValueDropdown)) {
            $this->optionValuesDropdown[] = $optionValueDropdown;
            $optionValueDropdown->setParent($this);
        }

        return $this;
    }

    public function removeOptionValueDropdown(Dropdown $optionValueDropdown): self
    {
        if ($this->optionValuesDropdown->contains($optionValueDropdown)) {
            $this->optionValuesDropdown->removeElement($optionValueDropdown);
            // set the owning side to null (unless already changed)
            if ($optionValueDropdown->getParent() === $this) {
                $optionValueDropdown->setParent(null);
            }
        }

        return $this;
    }

    public function getOptionValueField(): ?Field
    {
        return $this->optionValueField;
    }

    public function setOptionValueField(Field $optionValueField): self
    {
        $this->optionValueField = $optionValueField;

        // set the owning side of the relation if necessary
        if ($this !== $optionValueField->getParent()) {
            $optionValueField->setParent($this);
        }

        return $this;
    }
}
