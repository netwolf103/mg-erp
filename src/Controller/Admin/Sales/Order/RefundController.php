<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Entity\Sales\Order\Refund;
use App\Repository\Sales\Order\RefundRepository;
use App\Repository\Sales\Order\ItemRepository;

/**
 * Controller of order refund
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RefundController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_sales_order_refund';

    /**
     * @Route("/admin/sales/order/refund/{page}", name="admin_sales_order_refund", requirements={"page"="\d+"})
     */
    public function index(Request $request, RefundRepository $refund, int $page = 1)
    {
        $template = 'admin/sales/order/refund/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/refund/blocks/list.html.twig';
        }

        $results = $refund->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'refund' => $refund,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }

    /**
     * @Route("/admin/sales/order/refund/item/{id}/view", name="admin_sales_order_refund_view_item", requirements={"id"="\d+"})
     */
    public function view(ItemRepository $item, int $id)
    {
        $event = $this->verifyData([
            'item' => $item->find($id)
        ]);        

        $item = $event->getObject('item');

         return $this->render('admin/sales/order/refund/item/view.html.twig', [
            'item' => $item,
        ]);       
    }

    /**
     * @Route("/admin/sales/order/refund/{id}/agree", name="admin_sales_order_refund_agree", requirements={"id"="\d+"})
     */
    public function agree(RefundRepository $refund, int $id)
    {
        $event = $this->verifyData([
            'refund' => $refund->find($id)
        ]);         

        $refund = $event->getObject('refund');
        $refund->setStatus(Refund::STATUS_Y);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($refund);
        $entityManager->flush();

        $this->addSuccessFlash('The item refunded.');

        return $this->back();          
    }

    /**
     * @Route("/admin/sales/order/refund/{id}/refuse", name="admin_sales_order_refund_refuse", requirements={"id"="\d+"})
     */
    public function refuse(RefundRepository $refund, int $id)
    {
        $event = $this->verifyData([
            'refund' => $refund->find($id)
        ]);         

        $refund = $event->getObject('refund');
        $refund->setStatus(Refund::STATUS_R);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($refund);
        $entityManager->flush();

        $this->addSuccessFlash('The item refunded.');

        return $this->back();                   
    }

    /**
     * @Route("/admin/sales/order/refund/{id}/remove", name="admin_sales_order_refund_remove", requirements={"id"="\d+"})
     */
    public function remove(RefundRepository $refund, int $id)
    {
        $event = $this->verifyData([
            'refund' => $refund->find($id)
        ]);         

        $refund = $event->getObject('refund');

        $entityManager = $this->getDoctrine()->getManager();

        $itemEntity = $refund->getItem();
        $qtyCanceled = $refund->getQtyRefunded();
        $itemEntity->setQtyCanceled(-$qtyCanceled);

        if (!$refund->getTracks()->isEmpty()) {
            foreach ($refund->getTracks() as $track) {
                $entityManager->remove($track);
            }
        }

        $entityManager->remove($refund);
        $entityManager->persist($itemEntity);
        $entityManager->flush();

        $this->addSuccessFlash('The item removed.');

        return $this->back();                 
    }
}