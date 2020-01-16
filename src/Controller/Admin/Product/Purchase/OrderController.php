<?php

namespace App\Controller\Admin\Product\Purchase;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Controller\AdminControllerAbstract;

use App\Form\Product\Purchase\OrderType;
use App\Form\Product\Purchase\Order\CommentType;

use App\Entity\Product\Purchase\Order;
use App\Entity\Product\Purchase\Order\Comment;
use App\Repository\ProductRepository;
use App\Repository\Product\Purchase\OrderRepository;

/**
 * Controller of product purchase order.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_product_purchase_order';

    /**
     * @Route("/admin/product/purchase/order/{page}", name="admin_product_purchase_order", requirements={"page"="\d+"})
     */		
	public function index(Request $request, OrderRepository $order, int $page = 1)
	{
        $template = 'admin/product/purchase/order/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/product/purchase/order/blocks/list.html.twig';
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
     * @Route("/admin/product/purchase/order/view/{id}", name="admin_product_purchase_order_view", requirements={"id"="\d+"})
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
            'action' => $this->generateUrl('admin_product_purchase_order_add_comment', [
                'id' => $order->getId(),
            ]),
        ]);

        return $this->render('admin/product/purchase/order/view.html.twig', [
            'order' => $order,
            'formComment' => $formComment->createView(),
        ]);        
	}

    /**
     * @Route("/admin/product/purchase/order/receipt/{id}", name="admin_product_purchase_order_receipt", requirements={"id"="\d+"})
     */	
	public function receipt(TranslatorInterface $translator, OrderRepository $order, int $id)
	{
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        if ($order->isComplete()) {
        	$this->addErrorFlash('The order has been completed.');
        	return $this->back();
        }

        $entityManager = $this->getDoctrine()->getManager();

    	$comment = new Comment;
    	$comment->setParent($order);
    	$comment->setStatus(Order::STATUS_COMPLETE);
    	$comment->setComment($translator->trans('Received'));
    	$comment->setUser($this->getUser());
    	$comment->setCreatedAt(new \DateTimeImmutable());

    	$order->setStatus($comment->getStatus());

    	$entityManager->persist($comment);
        $entityManager->persist($order);

        $entityManager->flush();

        $this->addSuccessFlash('Received');

        return $this->back();
	}

   /**
     * @Route("/admin/product/purchase/order/{id}/add/comment", name="admin_product_purchase_order_add_comment", requirements={"id"="\d+"})
     */	
	public function addComment(Request $request, OrderRepository $order, int $id)
	{
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $form = $this->createForm(CommentType::class, new Comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setParent($order);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();

            $order->setStatus($comment->getStatus());

            $entityManager->persist($order);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addSuccessFlash('The comment added!');
        }

        return $this->back();        
	}

    /**
     * @Route("/admin/product/purchase/order/remove/{id}", name="admin_product_purchase_order_remove", requirements={"id"="\d+"})
     */	
	public function remove(Request $request, OrderRepository $order, int $id)
	{
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($order->getComments() as $comment) {
        	$entityManager->remove($comment);
        }

        foreach ($order->getItems() as $item) {
        	$entityManager->remove($item);
        }        

        $entityManager->remove($order);
        $entityManager->flush();

        $this->addSuccessFlash('The order removed.');

        return $this->back();        
	}

    /**
     * @Route("/admin/product/purchase/order/edit/{id}", name="admin_product_purchase_order_edit", requirements={"id"="\d+"})
     */	
	public function edit(Request $request, OrderRepository $order, int $id)
	{
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$order = $form->getData();
        	$order->setStatus(Order::STATUS_NEW);
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setUpdatedAt(new \DateTimeImmutable());

        	$entityManager = $this->getDoctrine()->getManager();

        	$totalQtyOrdered = $totalSubtotal = 0;
        	foreach ($order->getItems() as $item) {
                $item->setParent($order);
                $item->setCreatedAt(new \DateTimeImmutable());
                $item->setUpdatedAt(new \DateTimeImmutable());
                $item->setSubtotal($item->getPrice() * $item->getQtyOrdered());

                $entityManager->persist($item);

                $totalQtyOrdered	+= $item->getQtyOrdered();
                $totalSubtotal		+= $item->getSubtotal();
        	}

        	$order->setTotalQtyOrdered($totalQtyOrdered);
        	$order->setSubtotal($totalSubtotal);
        	$order->setGrandTotal($totalSubtotal + $order->getShippingAmount());

            $entityManager->persist($order);
 
            $entityManager->flush();

            $this->addSuccessFlash('The order has been added!');       	
        }

        return $this->render('admin/product/purchase/order/form.html.twig', [
            'form' => $form->createView(),
            'product' => $order->getParent(),
        ]);                
	}


    /**
     * @Route("/admin/product/purchase/{product_id}/order/add", name="admin_product_purchase_order_add", requirements={"product_id"="\d+"})
     */	
	public function add(Request $request, TranslatorInterface $translator, ProductRepository $product, int $product_id)
	{
        $event = $this->verifyData([
            'product' => $product->find($product_id)
        ]);

        $product = $event->getObject('product');

        $order = new Order;
        $order->setShippingAmount(0);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$order = $form->getData();
        	$order->setParent($product);
        	$order->setStatus(Order::STATUS_NEW);
            $order->setCreatedAt(new \DateTimeImmutable());
            $order->setUpdatedAt(new \DateTimeImmutable());

        	$entityManager = $this->getDoctrine()->getManager();

        	$totalQtyOrdered = $totalSubtotal = 0;
        	foreach ($order->getItems() as $item) {
                $item->setParent($order);
                $item->setCreatedAt(new \DateTimeImmutable());
                $item->setUpdatedAt(new \DateTimeImmutable());
                $item->setSubtotal($item->getPrice() * $item->getQtyOrdered());

                $entityManager->persist($item);

                $totalQtyOrdered	+= $item->getQtyOrdered();
                $totalSubtotal		+= $item->getSubtotal();
        	}

        	$order->setTotalQtyOrdered($totalQtyOrdered);
        	$order->setSubtotal($totalSubtotal);
        	$order->setGrandTotal($totalSubtotal + $order->getShippingAmount());

        	$postData = $request->get('order');
        	$comment = new Comment;
        	$comment->setParent($order);
        	$comment->setStatus(Order::STATUS_NEW);
        	$comment->setComment($postData['comment'] ?: $translator->trans('Purchased'));
        	$comment->setUser($this->getUser());
        	$comment->setCreatedAt(new \DateTimeImmutable());

        	$entityManager->persist($comment);
            $entityManager->persist($order);
 
            $entityManager->flush();

            $this->addSuccessFlash('The order has been added!');

            return $this->redirectToRoute('admin_product_purchase_order');        	
        }

        return $this->render('admin/product/purchase/order/form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);        
	}
}