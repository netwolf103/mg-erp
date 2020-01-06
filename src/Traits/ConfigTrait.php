<?php
namespace App\Traits;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Config\Core;

/**
 * Trait for datetime.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
trait ConfigTrait
{
	protected static $_configs;

	/**
	 * Load all configs.
	 * 
	 * @param  EntityManagerInterface $entityManager
	 * @return array
	 */
	public static function loadConfigs(EntityManagerInterface $entityManager)
	{
		if (is_null(static::$_configs)) {
			try {
				static::$_configs = $entityManager->getRepository(Core::class)->findAll();
			} catch (\Exception $e) {
				static::$_configs = [];
			}
		}

		return static::$_configs;
	}

	/**
	 * Return web name.
	 * 
	 * @param  string|null $default
	 * @return string|null
	 */
	public static function configWebname(?string $default = null)
	{
		return static::getConfigValue(Core::CONFIG_PATH_WEB_NAME, $default);
	}

	/**
	 * Return brand name.
	 * 
	 * @param  string|null $default
	 * @return string|null
	 */
	public static function configBrand(?string $default = null)
	{
		return static::getConfigValue(Core::CONFIG_PATH_WEB_BRAND, $default);
	}

	/**
	 * Return brand name.
	 * 
	 * @param  float|null $default
	 * @return string|null
	 */
	public static function configFreeShippingMinimumAmount(?float $default = null)
	{
		return static::getConfigValue(Core::CONFIG_PATH_WEB_FREE_SHIPPING_MINIMUM_AMOUNT, $default);
	}

	/**
	 * Return google merchants is enabled.
	 * 
	 * @return boolean
	 */
	public static function configGoogleMerchantsEnabled(): bool
	{
		return (bool) static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_ENABLED);
	}

	/**
	 * Return google merchants id.
	 * 
	 * @return string|null
	 */
	public static function configGoogleMerchantsId(): ?string
	{
		return static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_ID);
	}

	/**
	 * Return google auth config.
	 * 
	 * @return array
	 */
	public static function configGoogleAuth(): array
	{
		return [
			'type' 							=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_TYPE),
			'project_id' 					=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PROJECT_ID),
			'private_key_id' 				=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY_ID),
			'private_key' 					=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY),
			'client_email' 					=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_EMAIL),
			'client_id' 					=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_ID),
			'auth_uri' 						=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_URI),
			'token_uri' 					=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_TOKEN_URI),
			'auth_provider_x509_cert_url' 	=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_CERT_URL),
			'client_x509_cert_url'			=> static::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_CERT_URL),
		];
	}

	/**
	 * Return magento soap is enabled.
	 * 
	 * @return boolean
	 */
	public static function configMagentoEnabled(): bool
	{
		return (bool) static::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_ENABLED);
	}

	/**
	 * Return magneto api configs.
	 * 
	 * @return array
	 */
	public static function configMagentoApi(): array
	{
		return [
			'user' => static::configMagentoApiUser(),
			'key' => static::configMagentoApiKey(),
			'url' => static::configMagentoApiUrl(),
		];
	}

	/**
	 * Return magento soap user.
	 * 
	 * @return string|null
	 */
	public static function configMagentoApiUser(): ?string
	{
		return static::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_USER);
	}

	/**
	 * Return magento soap key.
	 *
	 * @return string|null
	 */
	public static function configMagentoApiKey(): ?string
	{
		return static::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_KEY);
	}

	/**
	 * Return magento soap url.
	 * 
	 * @return string|null
	 */
	public static function configMagentoApiUrl(): ?string
	{
		return static::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_URL);
	}

	/**
	 * Return config valur by path.
	 * 
	 * @param  string $path
	 * @param  string|null $default
	 * @return string|null
	 */
	public static function getConfigValue(string $path, $default = null)
	{
        $path = str_replace(':', '/', $path);

        $config = array_filter(static::$_configs, function($config) use ($path) {
            if ($config->getPath() == $path) {
                return $config;
            }
        });

        return reset($config) ? reset($config)->getValue() : $default;		
	}
}