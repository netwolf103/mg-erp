<?php

namespace App\Twig\Product;

use App\Entity\Product\Google;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension for google product
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GoogleExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */ 
    public function getFilters(): array
    {
        return [
            new TwigFilter('google_yesorno', [$this, 'getYesOrNo']),
        ];
    }

    public function getYesOrNo(?bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }
}
