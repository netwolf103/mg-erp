<?php

namespace App\Twig\Sales\Order;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Sales\Order\Item;

/**
 * Twig extension class of order item
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */       
    public function getFilters(): array
    {
        return [
            new TwigFilter('order_item_type', [$this, 'getType']),
        ];
    }

    /**
     * Get item type name.
     *
     * @param  int    $type
     * @return string|int
     */
    public function getType(int $type)
    {
        $types = Item::getTypeList();

        return $types[$type] ?? $type;
    }
}
