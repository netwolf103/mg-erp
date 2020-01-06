<?php

namespace App\Entity\Api;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Api\UserRepository")
 * @ORM\Table(name="api_user")
 */
class User
{
    /**
     * User status.
     */
    const STATUS_INACTIVE   = 0;
    const STATUS_ACTIVE     = 1;

    public static $_status;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $securityKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSecurityKey(): ?string
    {
        return $this->securityKey;
    }

    public function setSecurityKey(string $securityKey): self
    {
        $this->securityKey = $securityKey;

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

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->is_active == static::STATUS_ACTIVE;
    }

    public function generateAuthorizationKey(bool $base64 = false)
    {
        return $base64 ? base64_encode(sha1($this->username . $this->securityKey)) : sha1($this->username . $this->securityKey);
    }

    public function compareAuthorizationKey(string $str, bool $base64 = false)
    {
        $authorizationKey = $this->generateAuthorizationKey($base64);

        return $str === $authorizationKey;
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
}
