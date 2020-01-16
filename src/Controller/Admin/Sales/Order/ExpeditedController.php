<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Entity\Sales\Order\Expedited;
use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\ExpeditedRepository;

use App\Form\Sales\Order\ExpeditedType;

/**
 * Controller of order expedited.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ExpeditedController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_sales_order_expedited';

    /**
     * @Route("/admin/sales/order/expedited/{page}", name="admin_sales_order_expedited", requirements={"page"="\d+"})
     */
    public function index(Request $request, ExpeditedRepository $expedited, int $page = 1)
    {
        $template = 'admin/sales/order/expedited/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/expedited/blocks/list.html.twig';
        }

        $results = $expedited->getAll(
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
     * @Route("/admin/sales/order/expedited/view/{id}", name="admin_sales_order_expedited_view", requirements={"id"="\d+"})
     */ 
    public function view(ExpeditedRepository $expedited, int $id)
    {
        $event = $this->verifyData([
            'expedited' => $expedited->find($id)
        ]);

        $expedited = $event->getObject('expedited');

        return $this->render('admin/sales/order/expedited/view.html.twig', [
            'expedited' => $expedited,
        ]);  
    }

    /**
     * @Route("/admin/sales/order/expedited/cancel/{id}", name="admin_sales_order_expedited_cancel", requirements={"id"="\d+"})
     */  
    public function cancel(ExpeditedRepository $expedited, int $id)
    {
        $event = $this->verifyData([
            'expedited' => $expedited->find($id)
        ]);

        $expedited = $event->getObject('expedited');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($expedited);
        $entityManager->flush();

        $this->addSuccessFlash('The expedited canceled.');

        return $this->back();       
    }

    /**
     * @Route("/admin/sales/order/{id}/expedited/add", name="admin_sales_order_expedited_add", requirements={"id"="\d+"})
     */    
    public function add(Request $request, OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);

        $order = $event->getObject('order');

        $expedited = new Expedited;

        $form = $this->createForm(ExpeditedType::class, $expedited);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expedited = $form->getData();
            $expedited->setCreatedAt(new \DateTimeImmutable());
            $expedited->setParent($order);
            $expedited->setCreator($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expedited);
            $entityManager->flush();

            $this->addSuccessFlash('The order has expedited.');

            return $this->back('admin_sales_order');                         
        }

        return $this->render('admin/sales/order/expedited/form.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);              
    }
}
