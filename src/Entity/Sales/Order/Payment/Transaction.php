<?php

namespace App\Entity\Sales\Order\Payment;

use App\Entity\SaleOrder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Sales\Order\Payment\TransactionRepository")
 * @ORM\Table(name="sale_order_payment_transaction")
 */
class Transaction
{
    /**
     * Supported transaction types
     * @var string
     */
    const TYPE_PAYMENT = 'payment';
    const TYPE_ORDER   = 'order';
    const TYPE_AUTH    = 'authorization';
    const TYPE_CAPTURE = 'capture';
    const TYPE_VOID    = 'void';
    const TYPE_REFUND  = 'refund';

    protected static $_types;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="payment_transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent_order;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $txn_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $parent_txn_id;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $txn_type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParentOrder(): ?SaleOrder
    {
        return $this->parent_order;
    }

    public function setParentOrder(?SaleOrder $parent_order): self
    {
        $this->parent_order = $parent_order;

        return $this;
    }

    public function getTxnId(): ?string
    {
        return $this->txn_id;
    }

    public function setTxnId(?string $txn_id): self
    {
        $this->txn_id = $txn_id;

        return $this;
    }

    public function getParentTxnId(): ?string
    {
        return $this->parent_txn_id;
    }

    public function setParentTxnId(?string $parent_txn_id): self
    {
        $this->parent_txn_id = $parent_txn_id;

        return $this;
    }

    public function getTxnType(): ?string
    {
        return $this->txn_type;
    }

    public function setTxnType(?string $txn_type): self
    {
        $this->txn_type = $txn_type;

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

    public static function getTypeList(): array
    {
        if (is_null(static::$_types)) {
            static::$_types = array(
                static::TYPE_PAYMENT => 'Payment',
                static::TYPE_ORDER => 'Order',
                static::TYPE_AUTH => 'Authorization',
                static::TYPE_CAPTURE => 'Capture',
                static::TYPE_VOID => 'Void',
                static::TYPE_REFUND => 'Refund',
            );
        }
        return static::$_types;
    }     
}
