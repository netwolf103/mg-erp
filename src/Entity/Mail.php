<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \App\Entity\Mail\Folder;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MailRepository")
 */
class Mail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Mail\Folder", inversedBy="mails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $folder;

    /**
     * @ORM\Column(type="integer")
     */
    private $msgId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $fromName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fromAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $toAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ccAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bccAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $replyToAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function getMsgId(): ?int
    {
        return $this->msgId;
    }

    public function setMsgId(int $msgId): self
    {
        $this->msgId = $msgId;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(?string $fromName): self
    {
        $this->fromName = $fromName;

        return $this;
    }

    public function getFromAddress(): ?string
    {
        return $this->fromAddress;
    }

    public function setFromAddress(string $fromAddress): self
    {
        $this->fromAddress = $fromAddress;

        return $this;
    }

    public function getToAddress(): ?string
    {
        return $this->toAddress;
    }

    public function setToAddress(string $toAddress): self
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    public function getCcAddress(): ?string
    {
        return $this->ccAddress;
    }

    public function setCcAddress(?string $ccAddress): self
    {
        $this->ccAddress = $ccAddress;

        return $this;
    }

    public function getBccAddress(): ?string
    {
        return $this->bccAddress;
    }

    public function setBccAddress(?string $bccAddress): self
    {
        $this->bccAddress = $bccAddress;

        return $this;
    }

    public function getReplyToAddress(): ?string
    {
        return $this->replyToAddress;
    }

    public function setReplyToAddress(?string $replyToAddress): self
    {
        $this->replyToAddress = $replyToAddress;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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
}
