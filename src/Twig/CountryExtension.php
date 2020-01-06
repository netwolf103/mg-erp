<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Intl\Intl;

/**
 * Twig extension for country.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CountryExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */     
    public function getFilters(): array
    {
        return [
            new TwigFilter('country', [$this, 'countryFilter']),
        ];
    }

    /**
     * {@inheritdoc}
     */ 
    public function countryFilter($value, $displayLocale = null)
    {
        $countryNames = Intl::getRegionBundle()->getCountryNames($displayLocale);

        return $countryNames[$value] ?? $value;
    }
}
