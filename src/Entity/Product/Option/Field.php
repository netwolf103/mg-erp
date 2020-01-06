<?php

namespace App\Entity\Product\Option;

use App\Entity\Product\Option;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\Option\FieldRepository")
 * @ORM\Table(name="product_option_field")
 */
class Field
{
    const OPTION_TYPE = 'field';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product\Option", inversedBy="optionValueField", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $price_type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_characters;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Option
    {
        return $this->parent;
    }

    public function setParent(Option $parent): self
    {
        $this->parent = $parent;

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

    public function setPriceType(?string $price_type): self
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

    public function getMaxCharacters(): ?int
    {
        return $this->max_characters;
    }

    public function setMaxCharacters(?int $max_characters): self
    {
        $this->max_characters = $max_characters;

        return $this;
    }
}
