<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig extension class of query filter.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class QueryFiltersExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('getFilter', [$this, 'getQueryFilterValue']),
            new TwigFilter('getYesOrNo', [$this, 'getYesOrNo']),
        ];
    }

    /**
     * Get filter value.
     *
     * @param  array $value
     * @param  string $field
     * @param  string $default
     * @return string
     */
    public function getQueryFilterValue($value, $field, $default = '')
    {
        $field = explode('.', $field);

        if (count($field) <= 1) {
            $field = reset($field);
        } else {
            return $value[$field[0]][$field[1]] ?? $default;
        }

        return $value[$field] ?? $default;
    }

    /**
     * Return yes or no.
     * 
     * @param  bool|null $value
     * @return string
     */
    public function getYesOrNo($value): string
    {
        return $value ? 'Yes' : 'No';
    }
}
