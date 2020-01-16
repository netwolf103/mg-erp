<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Repository\Sales\OrderRepository;
use App\Entity\Sales\Order\ConfirmEmailHistory;
use App\Repository\Sales\Order\ConfirmEmailHistoryRepository;

use App\Form\Sales\Order\OrderEmailType;

use App\Message\Sales\Order\EmailPush;
use App\Message\Sales\Order\Email\ConfirmPush;

/**
 * Controller of order email.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class EmailController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/email/edit", name="admin_sales_order_email_edit", requirements={"order_id"="\d+"})
     */
    public function edit(Request $request, OrderRepository $order, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
        ]);        

        $order = $event->getObject('order');

        $form = $this->createForm(OrderEmailType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($order);

            $entityManager->flush();

            $this->dispatchMessage(new EmailPush($order_id));

            $this->addSuccessFlash('The order email updated!');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);
        }

        return $this->render('admin/sales/order/email/form.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/{order_id}/email/send/history/{page}", name="admin_sales_order_email_send_history", requirements={"order_id"="\d+", "page"="\d+"})
     */
    public function confirmEmailHistory(Request $request, OrderRepository $order, ConfirmEmailHistoryRepository $confirmEmailHistory, int $order_id, int $page = 1)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
        ]);        

        $order = $event->getObject('order');

        $template = 'admin/sales/order/email/history/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/email/history/blocks/list.html.twig';
        }

        $results = $confirmEmailHistory->getAll(
            ['parent' => $order_id],
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'order' => $order,
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);        
    }

    /**
     * @Route("/admin/sales/order/{order_id}/email/confirm", name="admin_sales_order_email_sendconfirm", requirements={"order_id"="\d+"})
     */
    public function sendConfirmEmail(OrderRepository $order, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id),
        ]);        

        $order = $event->getObject('order');

        $entityManager = $this->getDoctrine()->getManager();

        $confirmEmailHistoryEntity = new ConfirmEmailHistory;
        $confirmEmailHistoryEntity->setParent($order);
        $confirmEmailHistoryEntity->setUser($this->getUser());
        $confirmEmailHistoryEntity->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($confirmEmailHistoryEntity);
        $entityManager->flush();        

        $this->dispatchMessage(new ConfirmPush($confirmEmailHistoryEntity->getId()));

        $this->addSuccessFlash('The email has been sent successfully!');

        return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]);        
    }    
}