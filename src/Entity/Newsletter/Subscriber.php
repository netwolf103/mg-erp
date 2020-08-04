<?php

namespace App\Entity\Newsletter;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Newsletter\SubscriberRepository")
 * @ORM\Table(name="subscriber")
 */
class Subscriber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscriber_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subscriber_email;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer_firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer_middlename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer_lastname;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscriber_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriberId(): ?int
    {
        return $this->subscriber_id;
    }

    public function setSubscriberId(int $subscriber_id): self
    {
        $this->subscriber_id = $subscriber_id;

        return $this;
    }

    public function getSubscriberEmail(): ?string
    {
        return $this->subscriber_email;
    }

    public function setSubscriberEmail(string $subscriber_email): self
    {
        $this->subscriber_email = $subscriber_email;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCustomerFirstname(): ?string
    {
        return $this->customer_firstname;
    }

    public function setCustomerFirstname(?string $customer_firstname): self
    {
        $this->customer_firstname = $customer_firstname;

        return $this;
    }

    public function getCustomerMiddlename(): ?string
    {
        return $this->customer_middlename;
    }

    public function setCustomerMiddlename(?string $customer_middlename): self
    {
        $this->customer_middlename = $customer_middlename;

        return $this;
    }

    public function getCustomerLastname(): ?string
    {
        return $this->customer_lastname;
    }

    public function setCustomerLastname(?string $customer_lastname): self
    {
        $this->customer_lastname = $customer_lastname;

        return $this;
    }

    public function getSubscriberStatus(): ?int
    {
        return $this->subscriber_status;
    }

    public function setSubscriberStatus(int $subscriber_status): self
    {
        $this->subscriber_status = $subscriber_status;

        return $this;
    }
}
