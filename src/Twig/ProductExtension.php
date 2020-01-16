<?php

namespace App\Twig;

use App\Entity\Product;
use App\Entity\Product\CatalogInventory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Intl\Intl;

/**
 * Twig extension class of product
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductExtension extends AbstractExtension
{
    private $translator;

    /**
     * Init TranslatorInterface
     * 
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */ 
    public function getFilters(): array
    {
        return [
            new TwigFilter('product_status', [$this, 'getStatusLabel']),
            new TwigFilter('format_price', [$this, 'formatPrice']),
            new TwigFilter('format_options', [$this, 'formatOptions']),
            new TwigFilter('product_stock_status', [$this, 'getStockStatus']),
            new TwigFilter('product_line', [$this, 'getProductLine']),
        ];
    }

    /**
     * {@inheritdoc}
     */  
    public function getFunctions(): array
    {
        return [
            new TwigFunction('product_status_list', [$this, 'getStatusList']),
            new TwigFunction('product_status_stock_list', [$this, 'getStatusStockList']),
            new TwigFunction('product_line_list', [$this, 'getProductList']),
        ];
    }

    public function getProductLine(?int $value): ?string
    {
        $lines = Product::getLineTypeList();

        return $lines[$value] ?? $value;
    }

    public function getProductList(): array
    {
        return Product::getLineTypeList();
    }   
    
    /**
     * Return status list.
     * 
     * @return array
     */
    public function getStatusList(): array
    {
        return Product::getStatusList();
    }

    /**
     * Return status label.
     *
     * @param  int $value
     * @return string
     */
    public function getStatusLabel($value)
    {
        $status = Product::getStatusList();

        return $status[$value] ?? $value;
    }

    /**
     * Return stock list.
     * 
     * @return array
     */
    public function getStatusStockList(): array
    {
        return CatalogInventory::getStatusList();  
    }

    /**
     * Return stock.
     * 
     * @param  bool   $value
     * @return string
     */
    public function getStockStatus(?bool $value): ?string
    {
        $status = CatalogInventory::getStatusList();

        return $status[$value] ?? $value;
    }

    /**
     * Format price.
     *
     * @param  float $value
     * @param  string $symbol
     * @param  boolean $isNegative
     * @return string
     */
    public function formatPrice($value, $symbol = 'USD', $isNegative = false)
    {
        return ($isNegative ? '-' : '') . Intl::getCurrencyBundle()->getCurrencySymbol($symbol) . number_format($value, 2);
    }

    /**
     * Format order item custom options.
     *
     * @param  string $options
     * @param  string $locale
     * @return string
     */
    public function formatOptions($options, $locale = null)
    {
        if (!$options) {
           return '';
        }

        $options = unserialize($options);
        $options = $options['options'] ?? [];

        $str = '<dl class="item-options">';
        foreach ($options as $option) {
            $str .= sprintf('<dt>%s</dt><dd>%s</dd>', $this->translator->trans($option['label'], [], null, $locale), $option['print_value']);
        }
        $str .= '</dl>';

        return $str;
    }
}
