<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Entity\Sales\Order\Comment;

use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\CommentRepository;

use App\Form\Sales\Order\CommentType;

use App\Message\Sales\Order\CommentPush;

/**
 * Controller for order comment
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CommentController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/comments/{page}", name="admin_sales_order_comments", requirements={"order_id"="\d+", "page"="\d+"})
     */
    public function orderComments(Request $request, OrderRepository $order, CommentRepository $comment, int $order_id, int $page = 1)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $template = 'admin/sales/order/comment/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);
            $template = 'admin/sales/order/comment/blocks/list.html.twig';
        }      

        $results = $comment->getAll(
            array_merge($this->getSessionFilterQuery($request), ['parent' => $order_id]),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'order' => $event->getObject('order'),
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);        
    }

    /**
     * @Route("/admin/sales/order/{order_id}/comment/remove/{id}", name="admin_sales_order_comment_remove", requirements={"order_id"="\d+", "id"="\d+"})
     */
    public function removeComment(OrderRepository $order, CommentRepository $comment, int $order_id, int $id)
    {
        $event = $this->verifyData([
        	'order' => $order->find($order_id),
            'comment' => $comment->find($id)
        ]);

        $comment = $event->getObject('comment');

        $entityManager = $this->getDoctrine()->getManager();

        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();

        $this->addSuccessFlash('The comment removed.');

        return $this->redirectToRoute('admin_sales_order_comments', ['order_id' => $order_id]);           
    }

    /**
     * @Route("/admin/sales/order/addComment/{id}", name="admin_sales_order_addcomment", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function addComment(Request $request, OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);        

        $form = $this->createForm(CommentType::class, new Comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $event->getObject('order');

            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setParent($order);
            $comment->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();

            $order->setStatus($comment->getStatus());

            $entityManager->persist($order);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->dispatchMessage(new CommentPush($comment->getId()));

            $this->addSuccessFlash('The comment added!');
        }

        return $this->redirectToRoute('admin_sales_order_view', ['id' => $id]);
    }    	
}