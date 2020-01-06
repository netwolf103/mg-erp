<?php

namespace App\Entity\Sales\Order\Address;

use App\Entity\Sales\Order\Address;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\Address\GeoRepository")
 * @ORM\Table(name="sale_order_address_geo")
 */
class Geo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sales\Order\Address", inversedBy="Geos")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $lon;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $display_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $importance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Address
    {
        return $this->parent;
    }

    public function setParent(?Address $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(?string $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    public function setDisplayName(?string $display_name): self
    {
        $this->display_name = $display_name;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImportance(): ?string
    {
        return $this->importance;
    }

    public function setImportance(?string $importance): self
    {
        $this->importance = $importance;

        return $this;
    }
}
