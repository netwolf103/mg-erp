<?php

namespace App\Controller\Admin\Product;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Controller\AdminControllerAbstract;

use App\Repository\ProductRepository;
use App\Repository\Product\GoogleRepository;
use App\Message\Catalog\Category\Product\Google;
use App\Message\Catalog\Category\Product\Google\Push as GooglePush;
use App\Message\Catalog\Category\Product\Google\Delete as GoogleDelete;
use App\Form\Product\GoogleType;

/**
 * Controller of google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GoogleController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_product_google';

    /**
     * @Route("/admin/product/google/{page}", name="admin_product_google", requirements={"page"="\d+"})
     */	
	public function index(Request $request, GoogleRepository $google, int $page = 1)
	{
        $template = 'admin/product/google/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/product/google/blocks/list.html.twig';
        }

        $results = $google->getAll(
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
     * @Route("/admin/product/google/view/{id}", name="admin_product_google_view", requirements={"id"="\d+"})
     */	
	public function view(GoogleRepository $google, int $id)
	{
        $event = $this->verifyData([
            'google' => $google->find($id)
        ]);

        $google = $event->getObject('google'); 

        return $this->render('admin/product/google/view.html.twig', [
            'google' => $google,
        ]);        
	}

    /**
     * @Route("/admin/product/{product_id}/google/create", name="admin_product_google_create", requirements={"product_id"="\d+"})
     */	
	public function create(ProductRepository $product, int $product_id)
	{
        $event = $this->verifyData([
            'product' => $product->find($product_id)
        ]);

        $product = $event->getObject('product'); 

        $this->dispatchMessage(new Google($product_id));

        $this->addSuccessFlash('The product joined task queue.');

        return $this->redirectToRoute('admin_product'); 
	}

    /**
     * @Route("/admin/product/google/edit/{id}", name="admin_product_google_edit", requirements={"id"="\d+"})
     */	
	public function edit(Request $request, GoogleRepository $google, int $id)
	{
        $event = $this->verifyData([
            'google' => $google->find($id)
        ]);

        $google = $event->getObject('google');

        $form = $this->createForm(GoogleType::class, $google);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        	$google = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($google);
 
            $entityManager->flush();

            $this->addSuccessFlash('The product updated!');
        }

        return $this->render('admin/product/google/form.html.twig', [
            'form' => $form->createView(),
            'google' => $google,
        ]);                
	}

    /**
     * @Route("/admin/product/google/remove/{id}", name="admin_product_google_remove", requirements={"id"="\d+"})
     */	
	public function remove(GoogleRepository $google, int $id)
	{
        $event = $this->verifyData([
            'google' => $google->find($id)
        ]);

        $this->dispatchMessage(new GoogleDelete($id));

        $this->addSuccessFlash('The product joined delete queue.');

        return $this->back();                    
	}

    /**
     * @Route("/admin/product/google/push/{id}", name="admin_product_google_push", requirements={"id"="\d+"})
     */	
	public function push(GoogleRepository $google, int $id)
	{
        $event = $this->verifyData([
            'google' => $google->find($id)
        ]);

        $google = $event->getObject('google');

        $this->dispatchMessage(new GooglePush($id));

        $this->addSuccessFlash('The product joined task queue.');

        return $this->back();        
	}	
}