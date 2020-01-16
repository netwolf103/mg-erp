<?php

namespace App\Twig\Sales\Order;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use App\Entity\Sales\Order\Invoice;

/**
 * Twig extension class of order invoice
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class InvoiceExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */      
    public function getFilters(): array
    {
        return [
            new TwigFilter('invoice_state', [$this, 'getState']),
        ];
    }

    /**
     * Return state label.
     * 
     * @param  int $state
     * @return string
     */
    public function getState(int $state): string
    {
        $states = Invoice::getStates();

        return $states[$state] ?? $state;
    }
}
