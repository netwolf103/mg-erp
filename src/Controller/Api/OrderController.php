<?php

namespace App\Controller\Api;

use App\Controller\ApiControllerAbstract;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Repository\Sales\OrderRepository;
use App\Message\Sales\OrderPull;

/**
 * Controller of order api.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderController extends ApiControllerAbstract
{
    /**
     * @Route("/api/order/status", name="api_order_status", methods={"POST"})
     */	
	public function status(Request $request, OrderRepository $order)
	{
		try {

			$increment_id = $request->get('increment_id');
			
			if (empty($increment_id)) {
				throw new \Exception('Order id can\'t empty.');
			}

	        $event = $this->verifyData([
	            'order' => $order->findOneBy(['increment_id' => $increment_id])
	        ]);        

	        $order = $event->getObject('order');

	        $this->dispatchMessage(new OrderPull($order->getId()));

	        return $this->jsonSuccess([
	        	'message' => sprintf('Order # %s joined task queue.', $order->getIncrementId())
	        ]);
		} catch (NotFoundHttpException $e) {
			return $this->jsonError([
				'message' => $e->getMessage()
			]);
		}

	}
}