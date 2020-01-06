<?php

namespace App\Entity\Sales\Order;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use App\Entity\SaleOrder;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\CommentRepository")
 * @ORM\Table(name="sale_order_comment")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $is_customer_notified;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $entity_name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="order_comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getIsCustomerNotified(): ?int
    {
        return $this->is_customer_notified;
    }

    public function setIsCustomerNotified(?int $is_customer_notified): self
    {
        $this->is_customer_notified = $is_customer_notified;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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

    public function getEntityName(): ?string
    {
        return $this->entity_name;
    }

    public function setEntityName(?string $entity_name): self
    {
        $this->entity_name = $entity_name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
