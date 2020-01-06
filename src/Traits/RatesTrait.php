<?php
namespace App\Traits;

use App\Currency\Rates;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Trait for rates.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
trait RatesTrait
{
	protected static $_rates;

	/**
	 * Get rates object.
	 * 
	 * @param  ParameterBagInterface $parameter
	 * @return Rates
	 */
	public function getRates(ParameterBagInterface $parameter): Rates
	{
		if (self::$_rates == null) {
			self::$_rates = new Rates($parameter);
		}

		return self::$_rates;
	}
}