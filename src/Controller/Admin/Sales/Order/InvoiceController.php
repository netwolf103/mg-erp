<?php

namespace App\Controller\Admin\Sales\Order;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Repository\Sales\OrderRepository;
use App\Repository\Sales\Order\InvoiceRepository;

use App\Form\Sales\Order\Invoice\BatchPullType;
use App\Message\Sales\Order\InvoicePull;

/**
 * Controller for order invoice
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class InvoiceController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/invoice/{page}", name="admin_sales_order_invoice", requirements={"page"="\d+"})
     */
    public function index(Request $request, InvoiceRepository $invoice, int $page = 1)
    {
        $template = 'admin/sales/order/invoice/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/sales/order/invoice/blocks/list.html.twig';
        }

        $results = $invoice->getAll(
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
     * @Route("/admin/sales/order/invoice/pull/{order_id}", name="admin_sales_order_order_invoice_pull", requirements={"order_id"="\d+"})
     */
    public function pull(Request $request, OrderRepository $order, int $order_id = 0)
    {
        if ($order_id) {
            $orderEntity = $order->find($order_id);

            if (!$orderEntity) {
                $this->addErrorFlash(sprintf('Order #%d Not Found.', $order_id));
            } else {
                $this->addSuccessFlash('The order joined task queue.');
                $this->dispatchMessage(new InvoicePull($order_id));
            }

            return $this->back('admin_sales_order');
        }

        $form = $this->createForm(BatchPullType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->get('batch_pull');
            $increment_ids = $data['increment_ids'] ?? '';

            $increment_ids = array_unique(explode(',', $increment_ids));
            foreach ($increment_ids as $increment_id) {
                $orderEntity = $order->findOneBy(['increment_id' => $increment_id]);

                if (!$orderEntity) {
                   continue;
                }

                $this->dispatchMessage(new InvoicePull($orderEntity->getId()));
            }

            $this->addSuccessFlash('The orders joined task queue.');
        }

        return $this->render('admin/sales/order/invoice/pull/form.html.twig', [
            'form' => $form->createView(),
        ]);          
    }

    /**
     * @Route("/admin/sales/order/{id}/invoice/view", name="admin_sales_order_order_invoice_view", requirements={"id"="\d+"})
     */
    public function order(OrderRepository $order, int $id)
    {
        $event = $this->verifyData([
            'order' => $order->find($id)
        ]);        

        $order = $event->getObject('order');

        if (!$order->getInvoice()) {

            $this->addErrorFlash('The order invoice not found.');  

            return $this->redirectToRoute('admin_sales_order_view', ['id' => $id]);
        }

        return $this->render('admin/sales/order/invoice/view.html.twig', [
            'invoice' => $order->getInvoice(),
            'order' => $order
        ]);        
    }

    /**
     * @Route("/admin/sales/order/invoice/view/{id}", name="admin_sales_order_invoice_view", requirements={"id"="\d+"})
     */
    public function view(InvoiceRepository $invoice, int $id)
    {
        $event = $this->verifyData([
            'invoice' => $invoice->find($id)
        ]);        

        $invoice = $event->getObject('invoice');

        return $this->render('admin/sales/order/invoice/view.html.twig', [
            'invoice' => $invoice,
            'order' => $invoice->getParent()
        ]);        
    }

    /**
     * @Route("/admin/sales/order/invoice/print/{id}", name="admin_sales_order_invoice_print", requirements={"id"="\d+"})
     */
    public function print(InvoiceRepository $invoice, int $id)
    {
        $event = $this->verifyData([
            'invoice' => $invoice->find($id)
        ]);        

        $invoice = $event->getObject('invoice');      

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/sales/order/invoice/print.html.twig', [
            'title' => sprintf('Swetelove Invoice # %s', $invoice->getIncrementId()),
            'invoice' => $invoice,
            'order' => $invoice->getParent()            
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream(sprintf('invoice%s.pdf', date('Y-m-d_H-i-s')), [
            "Attachment" => true
        ]);        
    }
}
