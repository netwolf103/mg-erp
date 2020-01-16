<?php

namespace App\Controller\Admin\Sales\Order\Address;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\Address\HistoryRepository;

/**
 * Controller of order address history.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class HistoryController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/address/history/{address_id}/{page}", name="admin_sales_order_address_history", requirements={"order_id"="\d+", "address_id"="\d+", "page"="\d+"})
     */
    public function index(Request $request,  OrderRepository $order, HistoryRepository $history, int $order_id, int $address_id, int $page = 1)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $template = 'admin/sales/order/address/history/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/address/history/blocks/list.html.twig';
        }

        $results = $history->getAll(
            ['parent' => $address_id],
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'order' => $order,
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }
}
