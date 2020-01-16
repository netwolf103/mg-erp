<?php

namespace App\Api\Paypal\Reset;

use App\Api\Paypal\Reset\ResetInterface;

/**
 * Abstract class of paypal api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class ResetAbstract implements ResetInterface
{
	/**
	 * The api endpoint.
	 * @var string
	 */
	protected $apiEndPoint;

	/**
	 * Api access token.
	 * 
	 * @var string
	 */
	protected $accessToken;

	/**
	 * Token type.
	 * 
	 * @var string
	 */
	protected $tokenType;

	/**
	 * Constructor
	 * 
	 * @param array $params
	 */
	public function __construct(array $params)
	{
		$clientId = $params['clientId'] ?? '';
		$clientSecret = $params['clientSecret'] ?? '';
		$sandbox = $params['sandbox'] ?? 0;
		$version = $params['version'] ?? 'v1';

		$this->apiEndPoint = sprintf('https://api%s.paypal.com/%s/', ($sandbox ? '.sandbox' : ''), $version);

		$response = $this->getAccessToken($clientId, $clientSecret);

		$this->accessToken = $response->access_token ?? false;

		if(!$this->accessToken) {
			throw new \Exception('Get token failure');
		}

		$this->tokenType = $response->token_type ?? 'Bearer';
	}

	/**
	 * Call api token.
	 * 
	 * @param  string $clientId
	 * @param  string $clientSecret
	 * @return ?\stdClass
	 */
	public function getAccessToken(string $clientId, string $clientSecret): ?\stdClass
	{
		$curlOptions = [
			CURLOPT_USERPWD => $clientId.":".$clientSecret,
			CURLOPT_POSTFIELDS => 'grant_type=client_credentials'
		];

		return $this->call('oauth2/token', ['Content-Type: application/json'], $curlOptions);
	}

	/**
	 * Api caller.
	 * 
	 * @param  string $apiPath
	 * @param  array  $headers
	 * @param  array  $curlOptions
	 * @return \stdClass
	 */
	public function call(string $apiPath, array $headers = ['Content-Type: application/json'], array $curlOptions = []): ?\stdClass
	{
		$ch = curl_init();

		$requestUrl = $this->apiEndPoint . $apiPath;

		curl_setopt($ch, CURLOPT_URL, $requestUrl);

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if (isset($curlOptions[CURLOPT_POSTFIELDS])) {
			curl_setopt($ch, CURLOPT_POST, true);
		}

		curl_setopt_array($ch, $curlOptions);
		
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new \Exception('Curl error: ' . curl_error($ch));
		}

		curl_close($ch);

		$response = json_decode($response);

		if ($httpCode != '200') {
			$message = $response->error_description ?? '';

			if (!$message) {
				$message = $response->message ?? '';
			}

			throw new \Exception(sprintf('Paypl API error: [%s] %s', $message, $requestUrl));
		}		

		return $response;
	}
}