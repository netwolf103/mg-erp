<?php

namespace App\Twig\Sales\Order;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Sales\Order\Payment\Transaction;

/**
 * Twig extension class of order payment
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PaymentExtension extends AbstractExtension
{
    private $parameter;

    /**
     * Init TranslatorInterface
     * 
     * @param TranslatorInterface $translator
     */
    public function __construct(ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    /**
     * {@inheritdoc}
     */       
    public function getFilters(): array
    {
        return [
            new TwigFilter('payment_method', [$this, 'getPaymentMethod']),
        ];
    }

    /**
     * {@inheritdoc}
     */  
    public function getFunctions(): array
    {
        return [
            new TwigFunction('payment_transaction_type_list', [$this, 'getTypeList']),
            new TwigFunction('payment_method_list', [$this, 'getMethodList']),
        ];
    }    

    /**
     * Get payment method name.
     *
     * @param  string    $value
     * @return string|int
     */
    public function getPaymentMethod(string $value): string
    {
        $payment_methods = $this->getMethodList();

        return $payment_methods[$value] ?? $value;
    }

    /**
     * Get payment methods.
     * 
     * @return array
     */
    public function getMethodList(): array
    {
        $payment_methods = $this->parameter->get('payment_methods');

        foreach ($payment_methods as $key => $value) {
            if (is_array($value)) {
                unset($payment_methods[$key]);
            }
        }

        return $payment_methods;
    }

    /**
     * Get payment transaction types.
     * 
     * @return array
     */
    public function getTypeList(): array
    {
        return Transaction::getTypeList();
    }
}
