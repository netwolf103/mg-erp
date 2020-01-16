<?php
namespace App\Traits;

/**
 * Trait class of datetime.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
trait DatetimeTrait
{
	/**
	 * Convert date form timezone to other timezone.
	 * 
	 * @param  string      $date
	 * @param  string      $fromTimeZone
	 * @param  string|null $toTimeZone
	 * @return \DateTimeImmutable
	 */
	public function convertDatetime(string $date, string $fromTimeZone = 'UTC', ?string $toTimeZone = null)
	{
		$dateTimeImmutable = new \DateTimeImmutable($date, new \DateTimeZone($fromTimeZone));

		if (is_null($toTimeZone)) {
			$toTimeZone = @date_default_timezone_get();
		}

		return new \DateTimeImmutable(date(DATE_ATOM, $dateTimeImmutable->getTimestamp()), new \DateTimeZone($toTimeZone));
	}
}