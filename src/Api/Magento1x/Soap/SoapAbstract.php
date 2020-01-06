<?php

namespace App\Api\Magento1x\Soap;

use App\Api\Magento1x\Soap\SoapInterface;

/**
 * Magento 1.x soap client abstract class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class SoapAbstract implements SoapInterface
{
	/**
	 * The api endpoint.
	 * @var string
	 */
	protected $apiEndPoint;

	/**
	 * The api user.
	 * @var string
	 */
	protected $apiUser;

	/**
	 * The api key.
	 * @var string
	 */
	protected $apiKey;

	/**
	 * The SOAP client
	 * @var SoapClient
	 */
	protected $soapClient;

	/**
	 * The SOAP session id
	 * @var string
	 */
	protected $soapSessionId;

	/**
	 * Constructor
	 *
	 * @param string $domain
	 * @param string $apiUser
	 * @param string $apiKey
	 * @param array  $options
	 */
	public function __construct(string $domain = '', string $apiUser = '', string $apiKey = '', array $options = [])
	{
		if (!$domain || !$apiUser || !$apiKey) {
			return $this;
		}

		$this->apiEndPoint = sprintf('%s/api/v2_soap/?wsdl=1', rtrim($domain, '/'));
		$this->apiUser = $apiUser;
		$this->apiKey = $apiKey;
		
		$this->soapClient = new \SoapClient($this->apiEndPoint, $options);
        $this->soapSessionId = $this->soapClient->login($apiUser, $apiKey);
	}

	/**
	 * Copy a new session.
	 * 
	 * @param  SoapInterface $newSoapClient
	 * @return SoapInterface
	 */
	final public function copySession(SoapInterface $newSoapClient): SoapInterface
	{
		$newSoapClient
			->setApiEndPoint($this->apiEndPoint)
			->setSoapClient($this->soapClient)
			->setSoapSessionId($this->soapSessionId)
		;

		return $newSoapClient;
	}

	/**
	 * Get api endpoint.
	 * 
	 * @return string
	 */
	final public function getApiEndPoint(): string
	{
		return $this->apiEndPoint;
	}

	/**
	 * Set api endpoint.
	 * 
	 * @param string $url
	 * @return $this
	 */
	final public function setApiEndPoint(string $url): self
	{
		$this->apiEndPoint = $url;

		return $this;
	}

	/**
	 * Get SoapClient object.
	 * 
	 * @return \SoapClient
	 */
	final public function getSoapClient(): SoapInterface
	{
		return $this->soapClient;
	}

	/**
	 * Set SoapClient object.
	 * 
	 * @param \SoapClient $soapClient [description]
	 */
	final public function setSoapClient(\SoapClient $soapClient): self
	{
		$this->soapClient = $soapClient;

		return $this;
	}

	/**
	 * Get SoapClient sessionId.
	 * 
	 * @return string
	 */
	final public function getSoapSessionId(): string
	{
		return $this->soapSessionId;
	}

	/**
	 * Set SoapClient sessionId.
	 * 
	 * @param string $soapSessionId
	 */
	final public function setSoapSessionId($soapSessionId): self
	{
		$this->soapSessionId = $soapSessionId;

		return $this;
	}
}