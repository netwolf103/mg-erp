<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Intl\Intl;

use App\Controller\AdminControllerAbstract;

use App\Entity\Product;
use App\Entity\Product\Media;
use App\Entity\Product\Option;
use App\Entity\Product\Option\Dropdown;
use App\Entity\Product\Option\Field;
use App\Repository\ProductRepository;
use App\Form\ProductType;
use App\Message\Catalog\Category\ProductPull;
use App\Traits\RatesTrait;

/**
 * Controller of Product
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductController extends AdminControllerAbstract
{
    use RatesTrait;

    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_product';

    /**
     * @Route("/admin/product/{page}", name="admin_product", requirements={"page"="\d+"})
     */
    public function index(Request $request, ProductRepository $product, int $page = 1)
    {
        $template = 'admin/product/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/product/blocks/list.html.twig';
        }

        $results = $product->getAll(
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
     * @Route("/admin/product/add", name="admin_product_add")
     */
    public function add(Request $request)
    {
        $product = new Product;

        $option = new Option;
        $option->setType(Dropdown::OPTION_TYPE);
        $option->setSortOrder(1);

        $i = 1;
        foreach (range(5, 10, 0.25) as $value) {
            $dropdown = new Dropdown;
            $dropdown->setTitle(sprintf('%.2f(U.S)', $value));
            $dropdown->setPrice(0);
            $dropdown->setSortOrder($i++);
            $option->addOptionValueDropdown($dropdown);
            $product->addOption($option);
        }

        $option = new Option;
        $option->setType(Field::OPTION_TYPE);
        $option->setSortOrder(2);
        $field = new Field;
        $option->setOptionValueField($field);
        $product->addOption($option);        

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();

            $imageFiles = $form['images']->getData();
            if ($imageFiles) {
                foreach ($imageFiles as $imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    
                    $imageFile->move(
                        $this->getParameter('uploader_directory_catalog_local'),
                        $newFilename
                    );

                    $imageUrl = str_replace('public', '', $this->getParameter('upload_path_catalog_local')) . '/' . $newFilename;

                    $media = new Media;
                    $media->setProduct($product);
                    $media->setFile($newFilename);
                    $media->setUrl($imageUrl);
                    $entityManager->persist($media);
                }
            }         

            $optionTotalInventory = 0;
            foreach ($product->getOptions() as $option) {
                foreach ($option->getOptionValuesDropdown() as $optionValue) {
                    $optionValue->setParent($option);
                    $entityManager->persist($optionValue);
                    $optionTotalInventory += intval($optionValue->getInventory());
                }
                $option->setProduct($product);
                $entityManager->persist($option);
            }

            $imageFiles = $form['images']->getData();
            if ($imageFiles) {
                foreach ($imageFiles as $imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                    echo $newFilename;
                }
                exit;
            }

            if (!$product->getOptions()->isEmpty()) {
                $product->setHasOptions(1);
            }
            if ($product->getHasOptions()) {
                $product->setInventory($optionTotalInventory);
            }            

            $entityManager->persist($product);
 
            $entityManager->flush();

            $this->addSuccessFlash('The product added!');

            return $this->redirectToRoute('admin_product');
        }        

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/product/remove/{id}", name="admin_product_remove")
     */
    public function remove(ProductRepository $product, int $id)
    {
        $event = $this->verifyData([
            'product' => $product->find($id)
        ]);

        $product = $event->getObject('product');        

        $this->getDoctrine()->getManager()->remove($product);
        $this->getDoctrine()->getManager()->flush();

        $this->addSuccessFlash('The product removed.');

        return $this->back(); 
    }

    /**
     * @Route("/admin/product/edit/{id}", name="admin_product_edit")
     */
    public function edit(Request $request, ProductRepository $product, int $id)
    {
        $event = $this->verifyData([
            'product' => $product->find($id)
        ]);

        $product = $event->getObject('product'); 

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $imageFiles = $form['images']->getData();
            if ($imageFiles) {
                foreach ($imageFiles as $imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    
                    $imageFile->move(
                        $this->getParameter('uploader_directory_catalog_local'),
                        $newFilename
                    );

                    $imageUrl = str_replace('public', '', $this->getParameter('upload_path_catalog_local')) . '/' . $newFilename;

                    $media = new Media;
                    $media->setProduct($product);
                    $media->setFile($newFilename);
                    $media->setUrl($imageUrl);
                    $entityManager->persist($media);
                }
            }

            $optionTotalInventory = 0;
            foreach ($product->getOptions() as $option) {
                foreach ($option->getOptionValuesDropdown() as  $optionValue) {
                    $optionValue->setParent($option);
                    $entityManager->persist($optionValue);

                    $optionTotalInventory += intval($optionValue->getInventory());
                }
                $option->setProduct($product);
                $entityManager->persist($option);
            }

            if (!$product->getOptions()->isEmpty()) {
                $product->setHasOptions(1);
            }
            if ($product->getHasOptions()) {
                $product->setInventory($optionTotalInventory);
            }             

            $entityManager->persist($product);
 
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');
        }

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/product/pull/{id}", name="admin_product_pull", requirements={"id"="\d+"})
     */
    public function pull(ProductRepository $product, int $id)
    {
        $event = $this->verifyData([
            'product' => $product->find($id)
        ]);

        $product = $event->getObject('product'); 

        $this->dispatchMessage(new ProductPull($id));

        $this->addSuccessFlash('The product joined task queue.');

        return $this->back();         
    }

    /**
     * @Route("/admin/product/sku/{sku}/{symbol}", name="admin_product_size")
     */
    public function size(Request $request, ParameterBagInterface $parameter, TranslatorInterface $translator, ProductRepository $product, string $symbol = 'USD', string $sku = '')
    {
        $product = $product->findOneBy(['sku' => $sku]);

        $data = [
            $translator->trans('-- Please Select --') => ''
        ];

        if ($product) {
            foreach ($product->getOptionSizes() as $optionSize) {
                $title = $optionSize->getTitle();
                if ($optionSize->getPrice()) {

                    $price = $this->getRates($parameter)->convert($optionSize->getPrice(), $symbol);

                    $title .= sprintf('+%s%s', Intl::getCurrencyBundle()->getCurrencySymbol($symbol), $price);
                }

                $data[$title] = $optionSize->getId();
            }
        }

        return $this->json(['data' => $data]);
    }    
}
