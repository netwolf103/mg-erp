<?php

namespace App\Controller\Admin\Product\Stock;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Entity\Product;
use App\Repository\Product\Stock\AlertRepository;

/**
 * Controller of product stock alert.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AlertController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_product_stock_alert';

    /**
     * @Route("/admin/product/stock/alert/{page}", name="admin_product_stock_alert", requirements={"page"="\d+"})
     */
    public function index(Request $request, AlertRepository $alert, int $page = 1)
    {
        $template = 'admin/product/stock/alert/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/product/stock/alert/blocks/list.html.twig';
        }

        $results = $alert->getAll(
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
     * @Route("/admin/product/stock/alert/close/{id}", name="admin_product_stock_alert_close", requirements={"id"="\d+"})
     */
    public function close(AlertRepository $alert, int $id)
    {
        $event = $this->verifyData([
            'alert' => $alert->find($id)
        ]);

        $alert = $event->getObject('alert');

        $this->getDoctrine()->getManager()->remove($alert);
        $this->getDoctrine()->getManager()->flush();

        $this->addSuccessFlash('The alert removed.');

        return $this->back();                  
    }
}