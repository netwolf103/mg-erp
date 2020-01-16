<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Form\Sales\Order\RelatedType;
use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Related;
use App\Repository\Sales\Order\RelatedRepository;
use App\Repository\Sales\OrderRepository;

/**
 * Controller of related order.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RelatedController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/{order_id}/related/add", name="admin_sales_order_related_add", requirements={"order_id"="\d+"})
     */
    public function add(Request $request,  OrderRepository $order, RelatedRepository $relatedRepository, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $form = $this->createForm(RelatedType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postData  = $request->get('related');
            $relatedIncrementIds = $postData['increment_ids'] ?? '';
            $relatedIncrementIds = explode(',', $relatedIncrementIds);
            
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($relatedIncrementIds as $relatedIncrementId) {
                
                $relatedOrder = $entityManager
                    ->getRepository(SaleOrder::class)
                    ->findOneBy(['increment_id' => $relatedIncrementId]);

                if (!$relatedOrder || $relatedOrder->getId() == $order->getId()) {
                    continue;
                }

                $relationship = $relatedRepository->findOneBy([
                    'parent' => $order->getId(),
                    'order_id' => $relatedOrder->getId(),
                ]);
                if ($relationship) {
                    continue;
                }

                $relatedOrderEntity = new Related;
                $relatedOrderEntity->setParent($order);
                $relatedOrderEntity->setOrderId($relatedOrder->getId());
                $relatedOrderEntity->setIncrementId($relatedOrder->getIncrementId());

                $order->addRelatedOrder($relatedOrderEntity);
                $entityManager->persist($relatedOrderEntity);
            }

            $entityManager->flush($order);

            $this->addSuccessFlash('Related orders added.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]); 
        }        

        return $this->render('admin/sales/order/related/form.html.twig', [
        	'order' => $order,
        	'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/{order_id}/related/remove", name="admin_sales_order_related_remove", requirements={"order_id"="\d+"})
     */
    public function remove(Request $request,  OrderRepository $order, RelatedRepository $relatedRepository, int $order_id)
    {
        $event = $this->verifyData([
            'order' => $order->find($order_id)
        ]);        

        $order = $event->getObject('order');

        $form = $this->createForm(RelatedType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postData  = $request->get('related');
            $relatedIncrementIds = $postData['increment_ids'] ?? '';
            $relatedIncrementIds = explode(',', $relatedIncrementIds);
            
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($relatedIncrementIds as $relatedIncrementId) {
                
                $relatedOrder = $entityManager
                    ->getRepository(SaleOrder::class)
                    ->findOneBy(['increment_id' => $relatedIncrementId]);

                if (!$relatedOrder || $relatedOrder->getId() == $order->getId()) {
                    continue;
                }

                $relationship = $relatedRepository->findOneBy([
                    'parent' => $order->getId(),
                    'order_id' => $relatedOrder->getId(),
                ]);
                if (!$relationship) {
                    continue;
                }

                $entityManager->remove($relationship);
            }

            $entityManager->flush($order);

            $this->addSuccessFlash('Related orders added.');

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $order_id]); 
        }        

        return $this->render('admin/sales/order/related/form.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }    
}
