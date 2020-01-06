<?php

namespace App\Api\Paypal\Reset;

use App\Api\Paypal\Reset\ResetAbstract;

/**
 * Paypal reset api tracking class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Tracking extends ResetAbstract
{
	/**
	 * Add tracking numbers.
	 * 
	 * @param array $trackers
	 * @return \stdClass
	 * @see https://developer.paypal.com/docs/tracking/integrate/#add-tracking-information-with-tracking-numbers
	 */
	public function add(array $trackers): ?\stdClass
	{
		$response = $this->call('shipping/trackers-batch', [
			'Content-Type: application/json',
			sprintf('Authorization: %s %s', $this->tokenType, $this->accessToken)
		], [
			CURLOPT_POSTFIELDS => json_encode(['trackers' => [$trackers]])
		]);

		return $response;
	}

	/**
	 * Get tracking information.
	 * 
	 * @param  string $transaction_id
	 * @param  string $tracking_number
	 * @return \stdClass
	 * @see https://developer.paypal.com/docs/tracking/integrate/#show-tracking-information
	 */
	public function info(string $transaction_id, string $tracking_number): ?\stdClass
	{
		$response = $this->call(sprintf('shipping/trackers/%s-%s', $transaction_id, $tracking_number), [
			'Content-Type: application/json',
			sprintf('Authorization: %s %s', $this->tokenType, $this->accessToken)
		]);

		return $response;
	}
}