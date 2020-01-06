<?php

namespace App\Controller\Admin\Product;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\Sales\Order\ItemRepository;

/**
 * Controller for product sales.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SalesController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/product/sales/{page}", name="admin_product_sales", requirements={"page"="\d+"})
     */
    public function index(Request $request, ItemRepository $item, int $page = 1)
    {
        $template = 'admin/product/sales/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/product/sales/blocks/list.html.twig';
        }

        $results = $item->getAll(
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
     * @Route("/admin/product/{product_id}/sales/{page}", name="admin_product_view_sales", requirements={"product_id"="\d+", "page"="\d+"})
     */
    public function product(Request $request, ProductRepository $product, ItemRepository $item, int $product_id, int $page = 1)
    {
        $event = $this->verifyData([
            'product' => $product->find($product_id),
        ]);        

        $product = $event->getObject('product');

        $template = 'admin/product/sales/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/product/sales/blocks/list.html.twig';
        }

        $query = $this->getSessionFilterQuery($request);
        $query['sku'] = $product->getSku();

        $results = $item->getAll(
            $query,
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }    
}