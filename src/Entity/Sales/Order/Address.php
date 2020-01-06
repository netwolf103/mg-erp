<?php

namespace App\Entity\Sales\Order;

use App\Entity\Sales\Order\Address\History;
use App\Entity\Sales\Order\Address\Geo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\SaleOrder;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\AddressRepository")
 * @ORM\Table(name="sale_order_address")
 */
class Address
{
    /**
     * 地址类型
     * shipping: 收货地址
     * billing:  账单地址
     */
    const ADDRESS_SHIPPING = 'shipping';
    const ADDRESS_BILLING = 'billing';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $address_type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $country_id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="address")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isWrong;    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Address\History", mappedBy="parent")
     */
    private $historys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Address\Geo", mappedBy="parent")
     */
    private $Geos;    

    public function __construct()
    {
        $this->historys = new ArrayCollection();
        $this->Geos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressType(): ?string
    {
        return $this->address_type;
    }

    public function setAddressType(string $address_type): self
    {
        $this->address_type = $address_type;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountryId(): ?string
    {
        return $this->country_id;
    }

    public function setCountryId(string $country_id): self
    {
        $this->country_id = $country_id;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getFullName()
    {
        return $this->getFirstname() .' '. $this->getLastname();
    }

    /**
     * @return Collection|History[]
     */
    public function getHistorys(): Collection
    {
        return $this->historys;
    }

    public function addHistory(History $history): self
    {
        if (!$this->historys->contains($history)) {
            $this->historys[] = $history;
            $history->setParent($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->historys->contains($history)) {
            $this->historys->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getParent() === $this) {
                $history->setParent(null);
            }
        }

        return $this;
    }

    public function getIsWrong(): ?bool
    {
        return $this->isWrong;
    }

    public function setIsWrong(?bool $isWrong): self
    {
        $this->isWrong = $isWrong;

        return $this;
    }
    
    /**
     * @return Collection|Geo[]
     */
    public function getGeos(): Collection
    {
        return $this->Geos;
    }

    public function addGeo(Geo $geo): self
    {
        if (!$this->Geos->contains($geo)) {
            $this->Geos[] = $geo;
            $geo->setParent($this);
        }

        return $this;
    }

    public function removeGeo(Geo $geo): self
    {
        if ($this->Geos->contains($geo)) {
            $this->Geos->removeElement($geo);
            // set the owning side to null (unless already changed)
            if ($geo->getParent() === $this) {
                $geo->setParent(null);
            }
        }

        return $this;
    }    
}
