<?php

namespace App\Twig\Product;

use App\Entity\Product\Google;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension class of google product.
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

    /**
     * Return yes or no.
     *
     * @param  bool   $value
     * @return string
     */
    public function getYesOrNo(?bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }
}
