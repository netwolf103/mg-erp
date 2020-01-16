<?php

namespace App\Controller\Admin\Customer;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\CustomerRepository;
use App\Message\CustomerPull;

/**
 * Controller of customer.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CustomerController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_customer_customer';

    /**
     * @Route("/admin/customer/customer/{page}", name="admin_customer_customer", requirements={"page"="\d+"})
     */
    public function index(Request $request, CustomerRepository $customer, int $page = 1)
    {
        $template = 'admin/customer/customer/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/customer/customer/blocks/list.html.twig';
        }

        $results = $customer->getAll(
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
     * @Route("/admin/customer/customer/pull/{id}", name="admin_customer_pull", requirements={"id"="\d+"})
     */
    public function pull(Request $request, CustomerRepository $customer, int $id)
    {
        $this->verifyData([
            'customer' => $customer->find($id)
        ]);        

        $this->dispatchMessage(new CustomerPull($id));

        $this->addSuccessFlash('The customer joined task queue.');

        return $this->back();                
    }        
}
