<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Form\Sales\Order\Shipping\MethodType;
use App\Repository\Sales\OrderRepository;
use App\Repository\Config\Shipping\MethodRepository;
use App\Message\Sales\Order\ShippingmethodPush;
use App\Event\Sales\Order\ShippingEvent;
use App\Traits\RatesTrait;
use App\Traits\ShippingTrait;

class ShippingController extends AdminControllerAbstract
{
    use RatesTrait, ShippingTrait;

    /**
     * @Route("/admin/sales/order/{order_id}/shipping/method/edit", name="admin_sales_order_shipping_method_edit", requirements={"order_id"="\d+"})
     */
    public function edit(Request $request,  OrderRepository $order, MethodRepository $methodEntity, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');
        $oldOrder = clone $order;

        $form = $this->createForm(MethodType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $order = $form->getData();

            $methodEntity = $methodEntity->find($order->getShippingMethod());

            $this->setShippingMethod($this->container->get('parameter_bag'), $order, $methodEntity);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($order);

            $this->getDispatcher()->dispatch(new ShippingEvent($this->getUser(), $entityManager, $oldOrder, $order), ShippingEvent::UPDATE);

            $entityManager->flush();

            $this->dispatchMessage(new ShippingmethodPush($order_id));

            $this->addSuccessFlash('Shipping method updated.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]); 
        }        

        return $this->render('admin/sales/order/shipping/method/form.html.twig', [
        	'order' => $order,
        	'form' => $form->createView(),
        ]);
    }
}
