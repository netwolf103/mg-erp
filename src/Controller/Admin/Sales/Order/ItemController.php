<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Event\Sales\Order\ItemEvent;

use App\Entity\Sales\Order\Item;

use App\Repository\ProductRepository;
use App\Repository\Product\Option\DropdownRepository;
use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\ItemRepository;

use App\Form\Sales\Order\ItemType;
use App\Form\Sales\Order\Item\CancelType as ItemCancelType;
use App\Form\Sales\Order\Item\RefundType as ItemRefundType;

/**
 * Controller for Order Item
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/item/{id}/refund", name="admin_sales_order_item_refund", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function refund(Request $request, OrderRepository $order, ItemRepository $item, int $order_id, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
            'item' => $item->find($id),
        ]);        

        $order = $event->getObject('order');
        $item = $event->getObject('item');

        $form = $this->createForm(ItemRefundType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $item = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($item);

            $this->getDispatcher()->dispatch(new ItemEvent($this->getUser(), $entityManager, $order, $item, null, $request->get('refund')), ItemEvent::REFUND);

            $entityManager->flush();

            $this->addSuccessFlash('The item refunded.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]); 
        }

        return $this->render('admin/sales/order/item/action/form.html.twig', [
            'title' => 'Item Refund',
            'item' => $item,
            'order' => $order,
            'form' => $form->createView(),
        ]);                
    }

    /**
     * @Route("/admin/sales/order/{order_id}/item/{id}/cancel", name="admin_sales_order_item_cancel", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function cancel(Request $request, OrderRepository $order, ItemRepository $item, int $order_id, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
            'item' => $item->find($id),
        ]);         

        $order = $event->getObject('order');
        $item = $event->getObject('item');

        $form = $this->createForm(ItemCancelType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $item = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($item);

            $this->getDispatcher()->dispatch(new ItemEvent($this->getUser(), $entityManager, $order, $item, null, $request->get('cancel')), ItemEvent::CANCEL);

            $entityManager->flush();

            $this->addSuccessFlash('The item canceled.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);             
        }        

        return $this->render('admin/sales/order/item/action/form.html.twig', [
            'title' => 'Item Cancel',
            'item' => $item,
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/{order_id}/item/{id}/remove", name="admin_sales_order_item_remove", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function remove(OrderRepository $order, ItemRepository $item, int $order_id, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
            'item' => $item->find($id),
        ]);        

        $order = $event->getObject('order');
        $item = $event->getObject('item');

        $entityManager = $this->getDoctrine()->getManager();

        $this->getDoctrine()->getManager()->remove($item);
        $this->getDoctrine()->getManager()->flush();

        $this->getDispatcher()->dispatch(new ItemEvent($this->getUser(), $entityManager, $order, $item), ItemEvent::REMOVE);

        $this->addSuccessFlash('The item removed.');

        return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);
    }

    /**
     * @Route("/admin/sales/order/{order_id}/item/{id}/exchange", name="admin_sales_order_item_exchange", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function exchange(Request $request, OrderRepository $order, ItemRepository $item, ProductRepository $product, DropdownRepository $dropdown, int $order_id, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
            'item' => $item->find($id),
        ]);        

        $order      = $event->getObject('order');
        $originItem = $event->getObject('item');

        $item = new Item;
        $form = $this->createForm(ItemType::class, $item, [
            'order' => $order,
            'is_exchange' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $item = $form->getData();

            $product = $product->findOneBy(['sku' => $item->getSku()]);

            if (!$product) {
                $this->addErrorFlash(sprintf('No %s found.', $item->getSku()));

                return $this->redirectToRoute('admin_sales_order_item_edit', ['order_id' => $order_id, 'id' => $id]);
            }

            $item->setProduct($product);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($item); 

            try {
                $this->getDispatcher()->dispatch(
                    new ItemEvent(
                        $this->getUser(), 
                        $this->getDoctrine()->getManager(), 
                        $order, 
                        $item, 
                        $dropdown, 
                        $request->get('item'),
                        $originItem
                    ), ItemEvent::EXCHANGE );

                $entityManager->flush();

                $this->addSuccessFlash('The item exchanged.');
            } catch (\Exception $e) {
                $this->addErrorFlash($e->getMessage());
            }       

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);            
        }

        return $this->render('admin/sales/order/item/action/form.html.twig', [
            'title' => 'Item Exchange',
            'item' => $originItem,
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/{order_id}/item/add", name="admin_sales_order_item_add", requirements={"order_id"="\d+"})
     */
    public function add(Request $request, OrderRepository $order, ProductRepository $product, DropdownRepository $dropdown, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $item = new Item;

        $form = $this->createForm(ItemType::class, $item, [
            'order' => $order
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $item = $form->getData();

            $product = $product->findOneBy(['sku' => $item->getSku()]);

            if (!$product) {
                $this->addErrorFlash(sprintf('No %s found.', $item->getSku()));

                return $this->redirectToRoute('admin_sales_order_item_edit', ['order_id' => $order_id, 'id' => $id]);
            }

            $item->setProduct($product);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($item);        

            $this->getDispatcher()->dispatch(new ItemEvent($this->getUser(), $this->getDoctrine()->getManager(), $order, $item, $dropdown, $request->get('item')), ItemEvent::NEW );

            $entityManager->flush();

            $this->addSuccessFlash('The item added.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);            
        }

        return $this->render('admin/sales/order/item/form.html.twig', [
            'item' => $item,
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }    

    /**
     * @Route("/admin/sales/order/{order_id}/item/{id}/edit", name="admin_sales_order_item_edit", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function edit(Request $request, OrderRepository $order, ItemRepository $item, ProductRepository $product, DropdownRepository $dropdown, int $order_id, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
            'item' => $item->find($id),
        ]);        

        $order = $event->getObject('order');
        $item = $event->getObject('item');

        $form = $this->createForm(ItemType::class, $item, [
            'order' => $order
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();

            $product = $product->findOneBy(['sku' => $item->getSku()]);

            if (!$product) {
                $this->addErrorFlash(
                    sprintf('No %s found.', $item->getSku())
                );

                return $this->redirectToRoute('admin_sales_order_item_edit', ['order_id' => $order_id, 'id' => $id]);
            }

            $item->setProduct($product);

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($item);

            $this->getDispatcher()->dispatch(new ItemEvent($this->getUser(), $this->getDoctrine()->getManager(), $order, $item, $dropdown, $request->get('item')), ItemEvent::UPDATE);

            $entityManager->flush();

            $this->addSuccessFlash('The item updated.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);            
        }

        return $this->render('admin/sales/order/item/form.html.twig', [
            'item' => $item,
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }
}