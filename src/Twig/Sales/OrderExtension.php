<?php

namespace App\Twig\Sales;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use App\Entity\SaleOrder;
use App\Entity\Product\Purchase\Order as PurchaseOrder;

/**
 * Twig extension for order
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderExtension extends AbstractExtension
{
    private $translator;
    private $parameter;

    /**
     * Init TranslatorInterface
     * 
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, ParameterBagInterface $parameter)
    {
        $this->translator = $translator;
        $this->parameter = $parameter;       
    }

    /**
     * {@inheritdoc}
     */      
    public function getFilters(): array
    {
        return [
            new TwigFilter('order_type', [$this, 'getOrderType']),
            new TwigFilter('order_status', [$this, 'getStatus']),
            new TwigFilter('order_purchase_status', [$this, 'getPurchaseStatus']),
            new TwigFilter('order_platform_tracking_status', [$this, 'getPlatformTrackingStatus']),
            new TwigFilter('order_shipping_method', [$this, 'getShippingMethod']),
            new TwigFilter('order_shipping_method_class', [$this, 'getShippingMethodClass']),
        ];
    }

    /**
     * {@inheritdoc}
     */  
    public function getFunctions(): array
    {
        return [
            new TwigFunction('order_status_list', [$this, 'getStatusList']),
            new TwigFunction('order_purchase_status_list', [$this, 'getPurchaseStatusList']),
            new TwigFunction('order_type_list', [$this, 'getTypeList']),
        ];
    }

    /**
     * Return shipping method.
     * 
     * @param  string $value
     * @return string
     */
    public function getShippingMethod(?string $value): ?string
    {
        $methods = $this->parameter->get('shipping_methods');

        return $methods[$value] ?? $value;
    }

    /**
     * Return shipping method css class.
     * 
     * @param  string $value
     * @param  string $class
     * @return string
     */
    public function getShippingMethodClass(?string $value, string $class = 'text-danger font-weight-bold'): ?string
    {
        if ($value == 'flatrate_flatrate') {
            return $class;
        }

        return '';
    }

    /**
     * Return platform tracking status.
     * 
     * @param  bool   $value
     * @return string
     */
    public function getPlatformTrackingStatus(?bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }

    /**
     * Return status list.
     * 
     * @return array
     */
    public function getStatusList(): array
    {
        return SaleOrder::getStatusList();
    }

    /**
     * Return purchase order status list.
     * 
     * @return array
     */
    public function getPurchaseStatusList(): array
    {
        return PurchaseOrder::getStatusList();
    }    

     /**
     * Return type list.
     * 
     * @return array
     */
    public function getTypeList(): array
    {
        return SaleOrder::getOrderTypeList();
    }          

    /**
     * Return payment status
     *
     * @param  string $value
     * @return string
     */
    public function getStatus(string $value)
    {
        $status = SaleOrder::getStatusList();

        return $status[$value] ?? $value;
    }

    /**
     * Return purchase order status.
     * 
     * @param  string $value
     * @return string
     */
    public function getPurchaseStatus(?string $value): ?string
    {
        $status = PurchaseOrder::getStatusList();

        return $status[$value] ?? $value;        
    }

    /**
     * Return order type.
     *
     * @param  int    $value
     * @return string|int
     */
    public function getOrderType(int $value)
    {
        $types = SaleOrder::getOrderTypeList();

        return $types[$value] ?? $value;
    }
}
