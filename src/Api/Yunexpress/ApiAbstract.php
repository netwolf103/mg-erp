<?php

namespace App\Api\Yunexpress;

use App\Api\Yunexpress\ApiInterface;

/**
 * Abstract class of YunExpress api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class ApiAbstract implements ApiInterface {
	/**
	 * The api endpoint.
	 * @var string
	 */
	protected $apiEndPoint;

	/**
	 * The api id.
	 *
	 * @var string
	 */
	protected $apiId;

	/**
	 * The secret.
	 *
	 * @var string
	 */
	protected $apiSecret;

	/**
	 * The post data require "body" package.
	 *
	 * @var bool
	 */
	protected $postBodyPackage = true;

	/**
	 * Constructor
	 *
	 * @param array $params
	 */
	public function __construct(array $params) {
		$this->apiId = $params['apiId'] ?? '';
		$this->apiSecret = $params['apiSecret'] ?? '';
		$sandbox = $params['sandbox'] ?? false;

		$this->apiEndPoint = sprintf('http://%s.api.yunexpress.com/api', ($sandbox ? 'test' : 'oms'));
	}

	/**
	 * Get api request url.
	 *
	 * @return string
	 */
	final public function getApiEndPoint(): string {
		return $this->apiEndPoint;
	}

	/**
	 * Set post body package.
	 *
	 * @return string
	 */
	final public function setPostBodyPackage(bool $bool) {
		$this->postBodyPackage = $bool;
		return $this;
	}

	/**
	 * Get shipping code by country code.
	 *
	 * @return string
	 */
	final public function getShippingCodeByCountryId(string $countryId) {
		$codes = [
			'US' => 'THZXR',
			'DE' => 'THZXR',
			'IT' => 'BKZXR',
			'FR' => 'BKZXR',
			'GB' => 'THZXR',
			'MX' => 'THZXR',
			'CA' => 'THZXR',
			'ES' => 'THZXR',
			'AT' => 'THZXR',
			'NL' => 'THZXR',
			'AU' => 'THZXR',
			'BR' => 'THZXR',
			'DK' => 'THZXR',
			'BG' => 'THZXR',
			'HR' => 'THZXR',
			'CY' => 'THZXR',
			'CZ' => 'THZXR',
			'EE' => 'THZXR',
			'FI' => 'THZXR',
			'HU' => 'THZXR',
			'LV' => 'THZXR',
			'LT' => 'THZXR',
			'MT' => 'THZXR',
			'PL' => 'THZXR',
			'PT' => 'THZXR',
			'RO' => 'THZXR',
			'SK' => 'THZXR',
			'SI' => 'THZXR',
			'SE' => 'THZXR',
			'GR' => 'THZXR',
			'IE' => 'THZXR',
			'BE' => 'THZXR',
			'LU' => 'THZXR',
			'ZA' => 'THZXR',
		];

		return $codes[$countryId] ?? '';
	}

	/**
	 * Api caller.
	 *
	 * @param  array  $curlOptions
	 * @return array
	 */
	public function call($requestUri, array $data = [], array $curlOptions = []) {
		$ch = curl_init();

		$requestUrl = $this->getApiEndPoint() . $requestUri;

		curl_setopt($ch, CURLOPT_URL, $requestUrl);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);

		$curlHeader = [
			'Content-Type: application/json',
			'Authorization: Basic ' . base64_encode($this->apiId . '&' . $this->apiSecret),
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeader);

		curl_setopt_array($ch, $curlOptions);

		if ($data) {
			$postData = $this->postBodyPackage ? http_build_query(['body' => json_encode($data)]) : json_encode($data);

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new \Exception('Curl error: ' . curl_error($ch));
		}

		curl_close($ch);

		if ($httpCode != '200') {
			throw new \Exception(sprintf('YunExpress API error: %s', $requestUrl));
		}

		$response = json_decode($response);

		$response->requestUrl = $requestUrl;

		return $response;
	}
}