<?php

namespace App\Api\Oceanpayment;

use App\Api\Oceanpayment\ApiInterface;

/**
 * Oceanpayment api abstract class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class ApiAbstract implements ApiInterface
{
	/**
	 * The api endpoint.
	 * @var string
	 */
	protected $apiEndPoint;

	/**
	 * The secure code.
	 * 
	 * @var string
	 */
	protected $secureCode;

	/**
	 * The secure code.
	 * 
	 * @var string
	 */
	protected $account;

	/**
	 * The secure code.
	 * 
	 * @var string
	 */
	protected $terminal;		

	/**
	 * Request data.
	 * 
	 * @var array
	 */
	protected $data = [];

	/**
	 * Constructor
	 * 
	 * @param array $params
	 */
	public function __construct(array $params)
	{
		$account = $params['account'] ?? '';
		$terminal = $params['terminal'] ?? '';
		$sandbox = $params['sandbox'] ?? 0;
		$secureCode = $params['secureCode'] ?? '';

		$this->apiEndPoint = sprintf('https://secure.oceanpayment.com/gateway/service/%s', ($sandbox ? 'test' : 'pay'));

		$this->account = $account;
		$this->terminal = $terminal;
		$this->secureCode = $secureCode;
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
	 * Set api request url.
	 * 
	 * @param string $apiEndPoint
	 */
	final public function setApiEndPoint(string $apiEndPoint): self
	{
		$this->apiEndPoint = $apiEndPoint;

		return $this;
	}

	/**
	 * Add tracking number api url.
	 * 
	 * @return string
	 */
	final public function getUploadTrackingNumberApi(): string
	{
		return 'https://query.oceanpayment.com/service/uploadTrackingNo';
	}

	/**
	 * Get request data.
	 * 
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * Add request data.
	 * 
	 * @param array $data
	 */
	public function addData(array $data): self
	{
		$accountData = [
			'account' => $this->account,
			'terminal' => $this->terminal,
		];

		$this->data = array_merge($accountData, $data);
		$this->data['secureCode'] = $this->secureCode;
		$this->data['signValue'] = $this->getSignValue();

		return $this;
	}

	/**
	 * Get sing string.
	 * @return string
	 */
	public function getSignValue(): string
	{
		return strtoupper(hash("sha256", join('', array_values($this->getData()))));
	}

	/**
	 * Api caller.
	 * 
	 * @param  array  $curlOptions
	 * @return array
	 */
	public function call(array $curlOptions = []): array
	{
		$ch = curl_init();

		$requestUrl = $this->getApiEndPoint();

		curl_setopt($ch, CURLOPT_URL, $requestUrl);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);

		if ($this->getData()) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->getData()));
		}

		curl_setopt_array($ch, $curlOptions);
		
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new \Exception('Curl error: ' . curl_error($ch));
		}

		curl_close($ch);

		if ($httpCode != '200') {
			throw new \Exception(sprintf('Oceanpayment API error: %s', $requestUrl));
		}	

		$response = simplexml_load_string($response);
		$response = json_encode($response);
		$response = json_decode($response,TRUE);

		return $response;		
	} 
}