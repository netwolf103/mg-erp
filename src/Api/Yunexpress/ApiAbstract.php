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
	public function __construct(array $params)
	{
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
	final public function getApiEndPoint(): string
	{
		return $this->apiEndPoint;
	}

	/**
	 * Set post body package.
	 *
	 * @return string
	 */
	final public function setPostBodyPackage(bool $bool)
	{
		$this->postBodyPackage = $bool;
		return $this;
	}

	/**
	 * Get shipping code by country code.
	 *
	 * @return string
	 */
	final public function getShippingCodeByCountryId(string $countryId)
	{
		$codes = [
			'US' => 'THZXR', // 美国
			'DE' => 'THZXR', // 德国
			'IT' => 'BKZXR', // 意大利
			'FR' => 'BKZXR', // 法国
			'GB' => 'THZXR', // 英国
			'MX' => 'THZXR', // 墨西哥
			'CA' => 'THZXR', // 加拿大
			'ES' => 'THZXR', // 西班牙
			'AT' => 'THZXR', // 奥地利
			'NL' => 'THZXR', // 荷兰
			'AU' => 'THZXR', // 澳大利亚
			'BR' => 'THZXR', // 巴西
			'DK' => 'THZXR', // 丹麦
			'BG' => 'THZXR', // 保加利亚
			'HR' => 'THZXR', // 克罗地亚
			'CY' => 'THZXR', // 塞浦路斯
			'CZ' => 'THZXR', // 捷克
			'EE' => 'THZXR', // 爱沙尼亚
			'FI' => 'THZXR', // 芬兰
			'HU' => 'THZXR', // 匈牙利
			'LV' => 'THZXR', // 拉脱维亚
			'LT' => 'THZXR', // 立陶宛
			'MT' => 'THZXR', // 马耳他
			'PL' => 'THZXR', // 波兰
			'PT' => 'THZXR', // 葡萄牙
			'RO' => 'THZXR', // 罗马尼亚
			'SK' => 'THZXR', // 斯洛伐克
			'SI' => 'THZXR', // 斯洛文尼亚
			'SE' => 'THZXR', // 瑞典
			'GR' => 'THZXR', // 希腊
			'IE' => 'THZXR', // 爱尔兰
			'BE' => 'THZXR', // 比利时
			'LU' => 'THZXR', // 卢森堡
			'ZA' => 'THZXR', // 南非
		];

		return $codes[$countryId] ?? '';
	}

	/**
	 * Api caller.
	 *
	 * @param  array  $curlOptions
	 * @return array
	 */
	public function call($requestUri, array $data = [], array $curlOptions = [])
	{
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

		if ($response->Code == '0000') {
			$errorMessage = $response->Item[0]->Remark;
			throw new \Exception(sprintf('YunExpress API error: %s', $errorMessage));
		}

		$response->requestUrl = $requestUrl;

		return $response;
	}
}