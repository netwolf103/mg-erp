<?php

namespace App\Api\Oceanpayment;

use App\Api\Oceanpayment\ApiAbstract;

/**
 * Class of oceanpayment tracking.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Tracking extends ApiAbstract
{
	/**
	 * Api results status code.
	 * 
	 * @var array
	 */
	protected static $tracking_results = [
		'00' => 'Failure',
		'01' => 'Success',
		'02' => 'Invalid account',
		'03' => 'Invalid terminal',
		'04' => 'Error signature information',
		'05' => 'Account error',
		'06' => 'Invalid tracking_number',
		'07' => 'Invalid URL',
		'08' => 'Invalid Handler',
		'09' => 'Invalid payment_id',
		'10' => 'Required parameters incomplete',
		'11' => 'Invalid ip',
		'12' => 'Payment_id cannot be empty',
		'14' => 'Account cannot be empty',
		'15' => 'Terminal cannot be empty',
		'16' => 'Handler cannot be empty',
		'17' => 'Payment_id does not exist',
		'18' => 'Tracking_site cannot be empty',
		'19' => 'Invalid order_number',
		'99' => 'System error',
	];

	/**
	 * Constructor
	 * 
	 * @param array $params
	 */
	public function __construct(array $params)
	{
		parent::__construct($params);

		$this->setApiEndPoint($this->getUploadTrackingNumberApi());
	}

	/**
	 * Add tracking numbers.
	 * 
	 * @param array $data
	 */
	public function add(array $data): bool
	{
		$data['tracking_site'] = $data['tracking_site'] ?? 'N/A';
		$data['tracking_handler'] = $data['tracking_handler'] ?? 'N/A';

		$this->addData($data);

		$response = $this->call();
		$result_code = $response['tracking_results'] ?? false;

		if ($result_code != '01') {
			$message = static::$tracking_results[$result_code] ?? '';

			throw new \Exception(sprintf('Oceanpayment API error: Add Tracking Number -> [%s:%s]', $result_code, $message));
		}

		return true;
	}	
}