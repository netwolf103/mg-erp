<?php
namespace App;

/**
 * ERP base class
 */
final class __NGERP
{
	const NAME 		= 'NG ERP';
	const VERSION 	= '0.1.1';

	/**
	 * Class to array
	 *
	 * @return array
	 */
	static public function toArray(): array
	{
		return [
			'name' 		=> static::NAME,
			'version' 	=> static::VERSION,
		];
	}

	/**
	 * Class to string
	 *
	 * @return string
	 */
	static public function toString(): string
	{
		return sprintf('%s v%s', static::NAME, static::VERSION);
	}
}