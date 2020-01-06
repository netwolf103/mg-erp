<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Repository\Sales\OrderRepository;
use App\Repository\CustomerRepository;

/**
 * Controller for Dashboard
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class DashboardController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(OrderRepository $order, CustomerRepository $customer)
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'salesOrderCountAll' => $order->countAll(),
            'salesOrderCountNotShipments' => $order->countNotShipments(),
            'salesOrderCountTotay' => $order->countToday(),
            'salesOrderCountWeek' => $order->countWeek(),
            'salesOrderCountMonth' => $order->countMonth(),

            'customerCountAll' => $customer->countAll(),
            'customerCountTotay' => $customer->countToday(),
            'customerCountWeek' => $customer->countWeek(),
            'customerCountMonth' => $customer->countMonth(),            
        ]);
    }
}
