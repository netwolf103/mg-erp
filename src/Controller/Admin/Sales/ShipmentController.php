<?php

namespace App\Controller\Admin\Sales;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Entity\Sales\Order\Item;
use App\Entity\Sales\Order\Shipment\Track;

use App\Repository\Sales\Order\ShipmentRepository;
use App\Repository\Sales\OrderRepository;

use App\Form\Sales\Order\Shipment\TrackType;
use App\Form\Sales\Order\Shipment\ImportTrackNumberType;

use App\Message\Sales\Order\ShipmentPush;

/**
 * Controller of order shipment.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ShipmentController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/shipment/{page}", name="admin_sales_shipment", requirements={"page"="\d+"})
     */
    public function index(Request $request, ShipmentRepository $shipment, int $page = 1)
    {
        $template = 'admin/sales/order/shipment/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/shipment/blocks/list.html.twig';
        }

        $results = $shipment->getAll(
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
     * @Route("/admin/sales/order/shipment/import", name="admin_sales_shipment_import")
     */
    public function import(Request $request, OrderRepository $order)
    {
        $form = $this->createForm(ImportTrackNumberType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $csvFile = $form['csv']->getData();

            if ($csvFile) {

                if (($fp = fopen($csvFile->getPathname(), "r")) !== FALSE) {
                    while (($row = fgetcsv($fp, 1000, ",")) !== FALSE) {
                        if ($row[0] == 'increment_id') {
                            continue;
                        }

                        $order = $order->findOneBy(['increment_id' => $row[0]]);
                        if (!$order) {
                            continue;
                        }

                        $sku = $row[1];

                        $items = $order->getItems()->filter(function(Item $item) use ($sku){
                            return $item->getSku() == $sku;
                        });

                        if ($items->isEmpty()) {
                            continue;
                        }
                        $item = $items->first();

                        $data = [
                            'carrier_code' => 'custom',
                            'order_id' => $order->getId(),
                            'title' => $row[3],
                            'track_number' => $row[4],
                            'item:id:'.$item->getId() => $row[2],
                        ];
                        
                        $this->dispatchMessage(new ShipmentPush($data));
                    }
                }
            }

            $this->addSuccessFlash('The track number joined task queue.');

            return $this->redirectToRoute('admin_sales_shipment');                 
        }        

        return $this->render('admin/sales/order/shipment/import/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/sales/order/{id}/shipment/add", name="admin_sales_shipment_add", requirements={"id"="\d+"})
     */
    public function add(Request $request,  OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);        

        $order = $event->getObject('order');

        if (!$order->canShip()) {
            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order->getId()]);
        }

        $track = new Track;

        $form = $this->createForm(TrackType::class, $track, [
            'order_entity' => $order
        ]);  

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $track = $request->get('track');
            $track['order_id'] = $order->getId();

            $this->dispatchMessage(new ShipmentPush($track));

            $this->addSuccessFlash('The shipment joined task queue.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order->getId()]);                 
        }

        return $this->render('admin/sales/order/shipment/track/form.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
            'track' => $track
        ]);              
    }

    /**
     * {@inheritdoc}
     */
    public function __renderAction(): string
    {
        return $this->renderView('admin/sales/order/shipment/blocks/action/handle.html.twig');
    }
}