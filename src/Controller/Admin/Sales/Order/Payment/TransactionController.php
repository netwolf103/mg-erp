<?php

namespace App\Controller\Admin\Sales\Order\Payment;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\Payment\TransactionRepository;
use App\Message\Sales\Order\Payment\TransactionPull;

/**
 * Controller of order transaction.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TransactionController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/payment/transaction/index/{page}", name="admin_sales_order_payment_transaction", requirements={"page"="\d+"})
     */    
    public function index(Request $request, TransactionRepository $transaction, int $page = 1)
    {
        $template = 'admin/sales/order/payment/transaction/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/payment/transaction/blocks/list.html.twig';
        }

        $results = $transaction->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'order' => false,
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/{order_id}/payment/transaction/view", name="admin_sales_order_payment_transaction_view", requirements={"order_id"="\d+"})
     */
    public function order(Request $request, OrderRepository $order, int $order_id, TransactionRepository $transaction)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $template = 'admin/sales/order/payment/transaction/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/payment/transaction/blocks/list.html.twig';
        }

        $results = $transaction->getAll(
            ['increment_id' => $order->getIncrementId()],
            1,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'order' => $order,
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);        
    }

    /**
     * @Route("/admin/sales/order/{order_id}/payment/transaction/pull", name="admin_sales_order_payment_transaction_pull", requirements={"order_id"="\d+"})
     */
    public function pull(OrderRepository $order, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $this->dispatchMessage(new TransactionPull($order_id));

        $this->addSuccessFlash('The order joined task queue.');

        return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);   
    }
}
