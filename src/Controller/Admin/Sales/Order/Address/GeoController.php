<?php

namespace App\Controller\Admin\Sales\Order\Address;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;
use App\Repository\Sales\Order\AddressRepository;
use App\Repository\Sales\Order\Address\GeoRepository;
use App\Message\Sales\Order\Address\GeoPull;

/**
 * Controller of order address geo.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GeoController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/address/geo/pull/{address_id}", name="admin_sales_order_address_geo_pull", requirements={"address_id"="\d+"})
     */
    public function pull(Request $request,  AddressRepository $address, int $address_id)
    {
        $event = $this->verifyData([
            'address' => $address->find($address_id)
        ]);        

        $address = $event->getObject('address');

        $this->dispatchMessage(new GeoPull($address_id));

        $this->addSuccessFlash('The address joined geo pull task queue.');

        return $this->back();
    }

    /**
     * @Route("/admin/sales/order/address/geo/remove/{id}", name="admin_sales_order_address_geo_remove", requirements={"id"="\d+"})
     */
    public function remove(Request $request,  GeoRepository $geo, int $id)
    {
        $event = $this->verifyData([
            'geo' => $geo->find($id)
        ]);        

        $geo = $event->getObject('geo');

        $this->getDoctrine()->getManager()->remove($geo);
        $this->getDoctrine()->getManager()->flush();
        
        $this->addSuccessFlash('The geo data removed.');

        return $this->back();
    }
}
