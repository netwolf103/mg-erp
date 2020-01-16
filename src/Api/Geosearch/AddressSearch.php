<?php

namespace App\Api\Geosearch;

use App\Api\Geosearch\ApiInterface;

/**
 * Class of address search api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressSearch extends ApiAbstract
{
	/**
	 * Address geo search.
	 * 
	 * @param  array  $query
	 * @return array
	 * @see https://wiki.openstreetmap.org/wiki/Nominatim
	 */
	public function search(array $query): array
	{
		$response = $this->call($query);

		return $response;
	}
}