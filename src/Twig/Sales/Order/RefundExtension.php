<?php

namespace App\Twig\Sales\Order;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use App\Entity\Sales\Order\Refund;

/**
 * Twig extension class of order refund.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RefundExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */      
    public function getFilters(): array
    {
        return [
            new TwigFilter('refund_status', [$this, 'refundStatus']),
        ];
    }

    /**
     * {@inheritdoc}
     */  
    public function getFunctions(): array
    {
        return [
            new TwigFunction('refund_status_list', [$this, 'getStatusList']),
        ];
    }

    /**
     * Get status list.
     * 
     * @return array
     */
    public function getStatusList(): array
    {
        return Refund::getStatusLabel();
    }

    /**
     * Refund status.
     * 
     * @param  int    $value
     * @return string
     */
    public function refundStatus(int $value)
    {
        $status = Refund::getStatusLabel();

        return $status[$value] ?? $value;
    }
}
