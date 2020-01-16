<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Event\Sales\Order\AddressEvent;

use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\AddressRepository;

use App\Form\Sales\Order\AddressType;

use App\Message\Sales\Order\AddressPush;

/**
 * Controller of order address.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/address/edit/{id}", name="admin_sales_order_address_edit", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function edit(Request $request, OrderRepository $order, AddressRepository $address, int $order_id, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
            'address' => $address->find($id),
        ]);        

        $order = $event->getObject('order');
        $address = $event->getObject('address');
        $oldAddress = clone $address;

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($address);

            $this->getDispatcher()->dispatch(new AddressEvent($this->getUser(), $entityManager, $oldAddress, $address), AddressEvent::UPDATE);

            $entityManager->flush();

            $this->dispatchMessage(new AddressPush($id));

            $this->addSuccessFlash('The address updated!');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);
        }

        return $this->render('admin/sales/order/address/form.html.twig', [
            'address' => $address,
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/address/confirm/ok/{id}", name="admin_sales_order_address_confirm_ok", requirements={"id"="\d+"})
     */
    public function confirmOk(Request $request, AddressRepository $address, int $id)
    {
        $event = $this->verifyData([
            'address' => $address->find($id),
        ]);        

        $address = $event->getObject('address');

        $entityManager = $this->getDoctrine()->getManager();

        $address->setIsWrong(false);
        $entityManager->persist($address); 
        
        $entityManager->flush();

        $this->addSuccessFlash('Confirmation completed.'); 

        return $this->back();      
    }    
}