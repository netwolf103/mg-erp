<?php

namespace App\Api\Geosearch;

use App\Api\Geosearch\ApiInterface;

/**
 * Geosearch api abstract class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class ApiAbstract implements ApiInterface
{
	const USER_AGENT = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.108 Safari/537.36';

	/**
	 * Allow params map.
	 * 
	 * @var array
	 */
	protected $paramsMap = [
		'format',
		'json_callback',
		'accept-language',
		'q',
		'street',
		'city',
		'county',
		'state',
		'country',
		'postalcode',
		'countrycodes',
		'viewbox',
		'bounded',
		'polygon',
		'addressdetails',
		'email',
		'exclude_place_ids',
		'limit',
		'dedupe',
		'debug',
		'polygon_geojson',
		'polygon_kml',
		'polygon_svg',
		'polygon_text',
	];

	/**
	 * The api endpoint.
	 * @var string
	 */
	protected $apiEndPoint;

	/**
	 * Constructor
	 * 
	 * @param array $params
	 */
	public function __construct(string $format = 'json')
	{
		$this->apiEndPoint = sprintf('https://nominatim.openstreetmap.org/search.php?format=%s', $format);
	}

	/**
	 * Get api request url.
	 * 
	 * @return string
	 */
	final public function getApiEndPoint(): string
	{
		return $this->apiEndPoint;
	}

	/**
	 * Api caller.
	 * 
	 * @param  array  $curlOptions
	 * @return array
	 */
	public function call(array $query = [], array $curlOptions = []): array
	{
		$ch = curl_init();

		$requestUrl = $this->getApiEndPoint();

		foreach ($query as $key => $value) {
			if (!in_array($key, $this->paramsMap) || empty($value)) {
				unset($query[$key]);
				break;
			}
		}

		$query = http_build_query($query);

		$requestUrl .= '&'. $query;

		curl_setopt($ch, CURLOPT_URL, $requestUrl);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, static::USER_AGENT);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);

		curl_setopt_array($ch, $curlOptions);
		
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new \Exception('Curl error: ' . curl_error($ch));
		}

		curl_close($ch);

		if ($httpCode != '200') {
			throw new \Exception(sprintf('Nominatim API error: %s', $requestUrl));
		}	

		return json_decode($response);		
	} 			
}