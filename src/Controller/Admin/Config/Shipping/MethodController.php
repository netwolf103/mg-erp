<?php

namespace App\Controller\Admin\Config\Shipping;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\Config\Shipping\MethodRepository;

/**
 * Controller of shipping method.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MethodController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/config/shipping/method/{page}", name="admin_config_shipping_method", requirements={"page"="\d+"})
     */
    public function index(Request $request, MethodRepository $method, int $page = 1)
    {
        $template = 'admin/config/shipping/method/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/config/shipping/method/blocks/list.html.twig';
        }

        $results = $method->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }
}
