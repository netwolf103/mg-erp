<?php

namespace App\Controller\Admin\Sales\Order\Shipment;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\Sales\OrderRepository;
use App\Message\Sales\Order\Shipment\PlatformPush;

/**
 * Controller of shipment to platform.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PlatformController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/shipment/platform/push", name="admin_sales_order_shipment_platform_push", requirements={"order_id"="\d+"})
     */	
	public function push(OrderRepository $order, int $order_id)
	{
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $this->dispatchMessage(new PlatformPush($order_id));

        $this->addSuccessFlash('The order joined task queue.');

        return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);          
	}

    /**
     * @Route("/admin/sales/order/{order_id}/shipment/platform/setsync/{status}", name="admin_sales_order_shipment_platform_setsync", requirements={"order_id"="\d+", "status"="0|1"})
     */ 
    public function setsync(OrderRepository $order, int $order_id, int $status = 1)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $manager = $this->getDoctrine()->getManager();
        $order->setTrackingNumberToPlatformSynced(true);
        $manager->persist($order);
        $manager->flush();

        $this->addSuccessFlash('The order updated.');

        return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);          
    }       	
}