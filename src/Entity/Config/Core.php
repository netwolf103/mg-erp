<?php

namespace App\Entity\Config;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Config\CoreRepository")
 * @ORM\Table(name="core_config")
 */
class Core {
	/**
	 * Config path for web.
	 */
	const CONFIG_PATH_WEB_NAME = 'config:general:web:name';
	const CONFIG_PATH_WEB_BRAND = 'config:general:web:brand';
	const CONFIG_PATH_WEB_FREE_SHIPPING_MINIMUM_AMOUNT = 'config:general:web:free_shipping_minimum_amount';

	/**
	 * Config path for rates
	 */
	const CONFIG_PATH_RATES_USD = 'config:rates:usd';
	const CONFIG_PATH_RATES_AUD = 'config:rates:aud';
	const CONFIG_PATH_RATES_CAD = 'config:rates:cad';
	const CONFIG_PATH_RATES_EUR = 'config:rates:eur';
	const CONFIG_PATH_RATES_GBP = 'config:rates:gbp';
	const CONFIG_PATH_RATES_MXN = 'config:rates:mxn';
	const CONFIG_PATH_RATES_NZD = 'config:rates:nzd';
	const CONFIG_PATH_RATES_PHP = 'config:rates:php';
	const CONFIG_PATH_RATES_SGD = 'config:rates:sgd';

	/**
	 * Config path for magento api.
	 */
	const CONFIG_PATH_MAGENTO_API_ENABLED = 'config:magento:api:enabled';
	const CONFIG_PATH_MAGENTO_API_USER = 'config:magento:api:user';
	const CONFIG_PATH_MAGENTO_API_KEY = 'config:magento:api:key';
	const CONFIG_PATH_MAGENTO_API_URL = 'config:magento:api:url';

	/**
	 * Config path for google merchants.
	 */
	const CONFIG_PATH_GOOGLE_MERCHANTS_ENABLED = 'config:google:merchants:enabled';
	const CONFIG_PATH_GOOGLE_MERCHANTS_ID = 'config:google:merchants:id';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_TYPE = 'config:google:merchants:api:type';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_PROJECT_ID = 'config:google:merchants:api:project_id';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY_ID = 'config:google:merchants:api:private_key_id';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY = 'config:google:merchants:api:private_key';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_EMAIL = 'config:google:merchants:api:client_email';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_ID = 'config:google:merchants:api:client_id';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_URI = 'config:google:merchants:api:auth_uri';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_TOKEN_URI = 'config:google:merchants:api:token_uri';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_CERT_URL = 'config:google:merchants:api:auth_provider_x509_cert_url';
	const CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_CERT_URL = 'config:google:merchants:api:client_x509_cert_url';

	/**
	 * Config path for paypal api.
	 */
	const CONFIG_PATH_PAYPAL_API_ENABLED = 'config:paypal:api:enabled';
	const CONFIG_PATH_PAYPAL_API_SANDBOX = 'config:paypal:api:sandbox';
	const CONFIG_PATH_PAYPAL_API_VERSION = 'config:paypal:api:version';
	const CONFIG_PATH_PAYPAL_API_CLIENT_ID = 'config:paypal:api:client_id';
	const CONFIG_PATH_PAYPAL_API_CLIENT_SECRET = 'config:paypal:api:client_secret';

	/**
	 * Config path for oceanpayment api.
	 */
	const CONFIG_PATH_OCEANPAYMENT_API_ENABLED = 'config:oceanpayment:api:enabled';
	const CONFIG_PATH_OCEANPAYMENT_API_ACCOUNT = 'config:oceanpayment:api:account';
	const CONFIG_PATH_OCEANPAYMENT_API_TERMINAL = 'config:oceanpayment:api:terminal';
	const CONFIG_PATH_OCEANPAYMENT_API_SECURE_CODE = 'config:oceanpayment:api:secure_code';

	/**
	 * Config path for YunExpress api.
	 */
	const CONFIG_PATH_YUNEXPRESS_API_ENABLED = 'config:yunexpress:api:enabled';
	const CONFIG_PATH_YUNEXPRESS_API_SANDBOX = 'config:yunexpress:api:sandbox';
	const CONFIG_PATH_YUNEXPRESS_API_ACCOUNT = 'config:yunexpress:api:account';
	const CONFIG_PATH_YUNEXPRESS_API_SECRET = 'config:yunexpress:api:secret';
	const CONFIG_PATH_YUNEXPRESS_ORDER_PREFIX = 'config:yunexpress:order:prefix';

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $path;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $value;

	public function getId():  ? int {
		return $this->id;
	}

	public function getPath() :  ? string {
		return $this->path;
	}

	public function setPath(string $path) : self{
		$this->path = $path;

		return $this;
	}

	public function getValue():  ? string {
		return $this->value;
	}

	public function setValue( ? string $value) : self{
		$this->value = $value;

		return $this;
	}

	public static function setConfigs(array $configs, $repository) : ArrayCollection{
		$_configs = new ArrayCollection();

		foreach ($configs as $path => $value) {
			$path = str_replace(':', '/', $path);

			if (!$self = $repository->getValue($path)) {
				$self = new self;
			}

			$self->setPath($path);
			$self->setValue($value);

			$_configs->set($path, $self);
		}

		return $_configs;
	}
}
