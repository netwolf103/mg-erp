<?php

namespace App\Controller\Admin\Purchase;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Entity\Supplier;
use App\Repository\Purchase\SupplierRepository;
use App\Form\Purchase\SupplierType;

/**
 * Controller for Supplier
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SupplierController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_purchase_supplier';
        
    /**
     * @Route("/admin/purchase/supplier/{page}", name="admin_purchase_supplier", requirements={"page"="\d+"})
     */
    public function index(Request $request, int $page = 1)
    {
        $template = 'admin/purchase/supplier/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/purchase/supplier/blocks/list.html.twig';
        }

        $results = $this->getDoctrine()->getRepository(Supplier::class)->getAll(
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
     * @Route("/admin/purchase/supplier/add", name="admin_purchase_supplier_add")
     */
    public function add(Request $request)
    {
        $supplier = new Supplier;

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplier = $form->getData();
            $supplier
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($supplier);
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');

            return $this->redirectToRoute('admin_purchase_supplier');
        }

        return $this->render('admin/purchase/supplier/form.html.twig', [
            'form' => $form->createView(),
            'supplier' => $supplier,
        ]);
    }

    /**
     * @Route("/admin/purchase/supplier/remove/{id}", name="admin_purchase_supplier_remove", requirements={"id"="\d+"})
     */
    public function remove(SupplierRepository $supplier, int $id)
    {
        $event = $this->verifyData([
            'supplier' => $order->supplier($id)
        ]);        

        $this->getDoctrine()->getManager()->remove($event->get('supplier'));
        $this->getDoctrine()->getManager()->flush();

        $this->addSuccessFlash('The supplier removed.');

        return $this->back();
    }

    /**
     * @Route("/admin/purchase/supplier/edit/{id}", name="admin_purchase_supplier_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, SupplierRepository $supplier, int $id)
    {
        $event = $this->verifyData([
            'supplier' => $order->supplier($id)
        ]);

        $supplier = $event->get('supplier');       

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplier = $form->getData();
            $supplier
                ->setUpdatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($supplier);
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');
        }

        return $this->render('admin/purchase/supplier/form.html.twig', [
            'form' => $form->createView(),
            'supplier' => $supplier,
        ]);
    }
}
