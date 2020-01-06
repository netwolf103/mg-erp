<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Sales\Order\Expedited;
use App\Entity\Sales\Order\Comment;
use App\Entity\Product\Purchase\Order\Comment as PurchaseOrderComment;
use App\Entity\User\Role;
use App\Entity\User\LoginHistory;
use App\Entity\Sales\Order\ConfirmEmailHistory;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * User status.
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static $_status;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastname;

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $logged_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Comment", mappedBy="user")
     */
    private $order_comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Purchase\Order\Comment", mappedBy="user")
     */
    private $purchase_order_comments;    

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\Expedited", mappedBy="creator")
     */
    private $order_expedited;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User\LoginHistory", mappedBy="parent")
     */
    private $loginHistory;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sales\Order\ConfirmEmailHistory", mappedBy="user")
     */
    private $order_confirm_email_historys;            

    public function __construct()
    {
        $this->order_comments = new ArrayCollection();
        $this->purchase_order_comments = new ArrayCollection();
        $this->order_expedited = new ArrayCollection();
        $this->loginHistory = new ArrayCollection();
        $this->order_confirm_email_historys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }    

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getFullname(): ?string
    {
        return ($this->getFirstname() . $this->getLastname()) ?: $this->getUsername();
    }    

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [
            'ROLE_USER', 
            $this->role->getName()
        ];

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
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

    public function getLoggedAt(): ?\DateTimeInterface
    {
        return $this->logged_at;
    }

    public function setLoggedAt(\DateTimeInterface $logged_at): self
    {
        $this->logged_at = $logged_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getOrderComments(): Collection
    {
        return $this->order_comments;
    }

    public function addOrderComment(Comment $orderComment): self
    {
        if (!$this->order_comments->contains($orderComment)) {
            $this->order_comments[] = $orderComment;
            $orderComment->setUser($this);
        }

        return $this;
    }

    public function removeOrderComment(Comment $orderComment): self
    {
        if ($this->order_comments->contains($orderComment)) {
            $this->order_comments->removeElement($orderComment);
            // set the owning side to null (unless already changed)
            if ($orderComment->getUser() === $this) {
                $orderComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PurchaseOrderComment[]
     */
    public function getPurchaseOrderComments(): Collection
    {
        return $this->purchase_order_comments;
    }

    public function addPurchaseOrderComment(PurchaseOrderComment $orderComment): self
    {
        if (!$this->purchase_order_comments->contains($orderComment)) {
            $this->purchase_order_comments[] = $orderComment;
            $orderComment->setUser($this);
        }

        return $this;
    }

    public function removePurchaseOrderComment(PurchaseOrderComment $orderComment): self
    {
        if ($this->purchase_order_comments->contains($orderComment)) {
            $this->purchase_order_comments->removeElement($orderComment);
            // set the owning side to null (unless already changed)
            if ($orderComment->getUser() === $this) {
                $orderComment->setUser(null);
            }
        }

        return $this;
    }    

    /**
     * @return Collection|Expedited[]
     */
    public function getOrderExpedited(): Collection
    {
        return $this->order_expedited;
    }

    public function addOrderExpedited(Expedited $orderExpedited): self
    {
        if (!$this->order_expedited->contains($orderExpedited)) {
            $this->order_expedited[] = $orderExpedited;
            $orderExpedited->setCreator($this);
        }

        return $this;
    }

    public function removeOrderExpedited(Expedited $orderExpedited): self
    {
        if ($this->order_expedited->contains($orderExpedited)) {
            $this->order_expedited->removeElement($orderExpedited);
            // set the owning side to null (unless already changed)
            if ($orderExpedited->getCreator() === $this) {
                $orderExpedited->setCreator(null);
            }
        }

        return $this;
    }     

    public function getStatusList()
    {
        if (static::$_status == null) {
            static::$_status = [
                'Active' => static::STATUS_ACTIVE,
                'Inactive' => static::STATUS_INACTIVE,
            ];
        }

        return static::$_status;
    }

    /**
     * @return Collection|LoginHistory[]
     */
    public function getLoginHistory(): Collection
    {
        return $this->loginHistory;
    }

    public function addLoginHistory(LoginHistory $loginHistory): self
    {
        if (!$this->loginHistory->contains($loginHistory)) {
            $this->loginHistory[] = $loginHistory;
            $loginHistory->setParent($this);
        }

        return $this;
    }

    public function removeLoginHistory(LoginHistory $loginHistory): self
    {
        if ($this->loginHistory->contains($loginHistory)) {
            $this->loginHistory->removeElement($loginHistory);
            // set the owning side to null (unless already changed)
            if ($loginHistory->getParent() === $this) {
                $loginHistory->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConfirmEmailHistory[]
     */
    public function getOrderConfirmEmailHistorys(): Collection
    {
        return $this->order_confirm_email_historys;
    }

    public function addOrderConfirmEmailHistory(ConfirmEmailHistory $orderConfirmEmailHistory): self
    {
        if (!$this->order_confirm_email_historys->contains($orderConfirmEmailHistory)) {
            $this->order_confirm_email_historys[] = $orderConfirmEmailHistory;
            $orderConfirmEmailHistory->setUser($this);
        }

        return $this;
    }

    public function removeOrderConfirmEmailHistory(ConfirmEmailHistory $orderConfirmEmailHistory): self
    {
        if ($this->order_confirm_email_historys->contains($orderConfirmEmailHistory)) {
            $this->order_confirm_email_historys->removeElement($orderConfirmEmailHistory);
            // set the owning side to null (unless already changed)
            if ($orderConfirmEmailHistory->getUser() === $this) {
                $orderConfirmEmailHistory->setUser(null);
            }
        }

        return $this;
    }         
}
