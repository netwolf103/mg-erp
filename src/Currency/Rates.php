<?php

namespace App\Currency;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class of Rates.
 */
class Rates
{
    private $rates;

    /**
     * Init ParameterBag.
     *
     * @param ParameterBagInterface $parameter
     */
    public function __construct(ParameterBagInterface $parameter)
    {
        $this->rates = $parameter->get('currency_rates');
    }

    /**
     * Convert rate.
     * 
     * @param  float  $amount
     * @param  string $to
     * @param  int    $precision
     * @return float
     */
    public function convert(float $amount, string $to, int $precision = 2): float
    {
    	$rate = $this->getRate($to);
    	
    	return round($amount * $rate, $precision);
    }

    /**
     * Get rate.
     * 
     * @param  string  $rate
     * @return boolean
     */
    public function getRate(string $rate): float
    {
    	return $this->rates[$rate] ?? 1;
    }
}