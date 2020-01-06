<?php

namespace App\Controller\Admin\Sales;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Comment;

use App\Repository\Sales\OrderRepository;

use App\Form\Sales\Order\CommentType;

use App\Message\Sales\OrderPull;
use App\Message\Sales\Order\HoldPush as OrderHoldPush;
use App\Message\Sales\Order\UnHoldPush as OrderUnHoldPush;

/**
 * Controller for Order
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_sales_order';

    /**
     * @Route("/admin/sales/order/{page}", name="admin_sales_order", requirements={"page"="\d+"})
     */
    public function index(Request $request, OrderRepository $order, int $page = 1)
    {
        $template = 'admin/sales/order/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/blocks/list.html.twig';
        }

        $results = $order->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/remove/{id}", name="admin_sales_order_remove", requirements={"id"="\d+"})
     */
    public function remove(OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $entityManager = $this->getDoctrine()->getManager();

        if (!$order->getItems()->isEmpty()) {
            foreach ($order->getItems() as $item) {
                $entityManager->remove($item);
            }
        }

        if (!$order->getComments()->isEmpty()) {
            foreach ($order->getComments() as $comment) {
                $entityManager->remove($comment);
            }
        }

        if (!$order->getShipments()->isEmpty()) {
            foreach ($order->getShipments() as $shipment) {
                if (!$shipment->getTracks()->isEmpty()) {
                    foreach ($shipment->getTracks() as $track) {
                        $entityManager->remove($track);
                    }
                }

                if (!$shipment->getItems()->isEmpty()) {
                    foreach ($shipment->getItems() as $item) {
                        $entityManager->remove($item);
                    }
                }

                $entityManager->remove($shipment);
            }
        }

        if (!$order->getAddress()->isEmpty()) {
            foreach ($order->getAddress() as $address) {
                $entityManager->remove($address);
            }
        }        

        if ($order->getExpedited()) {
            $entityManager->remove($order->getExpedited());
        }
        
        if ($order->getInvoice()) {
            if (!$order->getInvoice()->getItems()->isEmpty()) {
                foreach ($order->getInvoice()->getItems() as $invoiceItem) {
                    $entityManager->remove($invoiceItem);
                }
            }
            $entityManager->remove($order->getInvoice());
        }

        if ($order->getPayment()) {
            $entityManager->remove($order->getPayment());
        }

        if (!$order->getPaymentTransactions()->isEmpty()) {
            foreach ($order->getPaymentTransactions() as $transaction) {
                $entityManager->remove($transaction);
            }
        }

        if (!$order->getRelatedOrders()->isEmpty()) {
            foreach ($order->getRelatedOrders() as $related) {
                $entityManager->remove($related);
            }
        }        

        $entityManager->remove($order);
        $entityManager->flush();

        $this->addSuccessFlash('The order removed.');

        return $this->back();                  
    }

    /**
     * @Route("/admin/sales/order/view/{id}", name="admin_sales_order_view", requirements={"id"="\d+"})
     */
    public function view(OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $comment = new Comment;
        $comment->setStatus($order->getStatus());

        $formComment = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('admin_sales_order_addcomment', [
                'id' => $order->getId(),
            ]),
        ]);

        return $this->render('admin/sales/order/view.html.twig', [
            'order' => $order,
            'formComment' => $formComment->createView(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/pull/{id}", name="admin_sales_order_pull", requirements={"id"="\d+"})
     */
    public function pull(OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $this->dispatchMessage(new OrderPull($id));

        $this->addSuccessFlash('The order joined task queue.');

        return $this->_back();         
    }

    /**
     * @Route("/admin/sales/order/hold/{id}", name="admin_sales_order_hold", requirements={"id"="\d+"})
     */
    public function hold(OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $entityManager = $this->getDoctrine()->getManager();
        $order->setStatus(SaleOrder::ORDER_STATUS_HOLDED);
        $order->setState(SaleOrder::ORDER_STATUS_HOLDED);

        $entityManager->persist($order);

        $entityManager->flush();

        $this->dispatchMessage(new OrderHoldPush($id));

        $this->addSuccessFlash('The order joined task queue.');

        return $this->_back();  
    }

    /**
     * @Route("/admin/sales/order/unhold/{id}", name="admin_sales_order_unhold", requirements={"id"="\d+"})
     */
    public function unhold(OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $entityManager = $this->getDoctrine()->getManager();
        $order->setStatus(SaleOrder::ORDER_STATUS_PROCESSING);
        $order->setState(SaleOrder::ORDER_STATUS_PROCESSING);
            
        $entityManager->persist($order);

        $entityManager->flush();

        $this->dispatchMessage(new OrderUnHoldPush($id));

        $this->addSuccessFlash('The order joined task queue.');

        return $this->_back(); 
    }

    /**
     * Back to url.
     * 
     * @param  array
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function _back(array $parameters = [])
    {
        if (!$parameters) {
            $request = $this->getCurrentRequest();

            if ($request) {
                $parameters = $request->get('_route_params');
            }
        }

        return $this->back('admin_sales_order_view', $parameters);
    }  
}
