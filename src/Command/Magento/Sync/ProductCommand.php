<?php

namespace App\Command\Magento\Sync;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

use App\Api\Magento1x\Soap\Catalog\ProductSoap;
use App\Api\Magento1x\Soap\Catalog\ProductAttributeMediaSoap;
use App\Api\Magento1x\Soap\Catalog\ProductCustomOptionSoap;
use App\Api\Magento1x\Soap\Catalog\CatalogInventorySoap;

use App\Command\Magento\SyncCommand;

use App\Entity\Product;
use App\Entity\Product\Media;
use App\Entity\Product\Option as ProductOption;
use App\Entity\Product\CatalogInventory;

/**
 * Command for catalog product
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductCommand extends SyncCommand
{
    protected static $entityType = 'magento.catalog.product'; 
    protected static $defaultName = 'app:magento:sync-catalog-product';
    protected static $title =  'Get Magento Products';
    protected static $description =  'Sync all products.';
    protected static $complexFilterKey =  'product_id';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setSyncRecord();
        
        $io = new SymfonyStyle($input, $output);

        $io->title(static::$title);

        $entityManager = $this->getDoctrine()->getManager();

        $client = $this->createClient($input, ProductSoap::class);

        $io->section('Loading Product List');

        $products = $client->callCatalogProductList($this->getFilter($input->getOption('filter')));
        $total = count($products);
        $adder = 0;
    
        // Product List
        foreach ($products as $product) {
            $product_id = $product->product_id ?? false;

            if (!$product_id) {
                continue;
            }

            $product_sku = $product->sku ?? false;

            if (!$product_sku) {
                $io->warning(sprintf('Id: %s SKU not exists.', $product_id));
                continue;
            }            

            $io->writeln(sprintf('%s # %d/%d', $product->sku, $total, ++$adder));
            $io->section('Loading Product');

            $productEntity = $entityManager->getRepository(Product::class)->findOneBy([
                'sku' => $product->sku,
            ]);
            if ($productEntity) {
                $io->warning('Existed');
                continue;
            }

            $product = $client->callCatalogProductInfo($product_id);

            // Product Entity
            $productEntity = new Product();

            $productEntity->setSku($product->sku);
            $productEntity->setTypeId($product->type_id);
            $productEntity->setName($product->name);
            $productEntity->setDescription($product->description);
            $productEntity->setStatus($product->status);
            $productEntity->setUrlPath($product->url_path);
            $productEntity->setProductUrl(sprintf('%s/%s', rtrim($this->getApiConfig('url'), '/'), $product->url_path));
            $productEntity->setVisibility($product->visibility);
            $productEntity->setHasOptions($product->has_options);
            $productEntity->setPrice($product->price);
            $productEntity->setSpecialPrice($product->special_price ?? 0);
            $productEntity->setCreatedAt($this->convertDatetime($product->created_at));
            $productEntity->setUpdatedAt($this->convertDatetime($product->updated_at));

            $io->section('Loading Catalog Inventory');
            $inventory = $client->copySession(new CatalogInventorySoap())->callCatalogInventoryStockItemList([$product->product_id]);
            $inventory = array_shift($inventory);
            if ($inventory) {
                $catalogInventoryEntity = new CatalogInventory;
                $catalogInventoryEntity->setProduct($productEntity);
                $catalogInventoryEntity->setSku($inventory->sku);
                $catalogInventoryEntity->setQty($inventory->qty);
                $catalogInventoryEntity->setIsInStock($inventory->is_in_stock);

                $entityManager->persist($catalogInventoryEntity);                
            }

            $io->section('Loading Product Images');
            $clientMedia = $client->copySession(new ProductAttributeMediaSoap());
            $mediaList = $clientMedia->callCatalogProductAttributeMediaList($product->product_id);
            foreach ($mediaList as $_media) {
                // Product Media Entity
                $newMediaUrl = $this->downloadMediaFile($_media);
                $productMediaEntity = new Media();

                $productMediaEntity->setProduct($productEntity);
                $productMediaEntity->setFile($_media->file);
                $productMediaEntity->setLabel($_media->label);
                $productMediaEntity->setPosition($_media->position);
                $productMediaEntity->setExclude($_media->exclude);
                $productMediaEntity->setUrl($newMediaUrl);

                $entityManager->persist($productMediaEntity);
            }

            $io->section('Loading Custom Options');
            $clientOption = $client->copySession(new ProductCustomOptionSoap());
            $customOptionList = $clientOption->callCatalogProductCustomOptionList($product->product_id);

            foreach ($customOptionList as $_customOption) {
                $io->section(sprintf('Loading Custom Option Info: %s', $_customOption->title));
                $_customOption = $clientOption->callCatalogProductCustomOptionInfo($_customOption->option_id);

                // Product Option Entity
                $productOptionEntity = new ProductOption();
                $productOptionEntity->setProduct($productEntity);
                $productOptionEntity->setTitle($_customOption->title);
                $productOptionEntity->setType($_customOption->type);
                $productOptionEntity->setSortOrder($_customOption->sort_order);
                $productOptionEntity->setIsRequire($_customOption->is_require);

                foreach ($_customOption->additional_fields as $_customOptionValue) {
                    // Product Option Value Entity
                    $class = sprintf('App\Entity\Product\Option\%s', ucfirst(str_replace('_', '', $_customOption->type)));
                    if (class_exists($class)) {
                        $optionValueEntity = new $class;
                        $optionValueEntity->setParent($productOptionEntity);

                        if (method_exists($optionValueEntity, 'setTitle')) {
                            $optionValueEntity->setTitle($_customOptionValue->title);
                        }

                        if (method_exists($optionValueEntity, 'setSortOrder')) {
                            $optionValueEntity->setSortOrder($_customOptionValue->sort_order);
                        } 
                        
                        if (method_exists($optionValueEntity, 'setInventory')) {
                            $optionValueEntity->setInventory(0);
                        }

                        if (method_exists($optionValueEntity, 'setMaxCharacters')) {
                            $optionValueEntity->setMaxCharacters($_customOptionValue->max_characters);
                        }                                                                        

                        $optionValueEntity->setPrice($_customOptionValue->price);
                        $optionValueEntity->setPriceType($_customOptionValue->price_type);

                        $entityManager->persist($optionValueEntity);                                                 
                    }
                }

                $entityManager->persist($productOptionEntity);
            }

            $this->getSyncRecord()->setUpdatedAt(new \DateTimeImmutable());
            $this->getSyncRecord()->setLastEntityId($product_id);

            $entityManager->persist($productEntity);
            $entityManager->persist($this->getSyncRecord());

            $entityManager->flush();
        }

        $io->success('Products successfully synced.');
    }

    /**
     * Download meida file.
     *
     * @param  stdClass $meida
     * @return string
     */
    private function downloadMediaFile($meida): string
    {
        $filesystem = new Filesystem();
        $targetFile = $this->getContainer()->getParameter('uploader_directory') . sprintf('/catalog/product%s', $meida->file);

        if (!$filesystem->exists(dirname($targetFile))) {
            $filesystem->mkdir(dirname($targetFile));
        }

        $filesystem->copy($meida->url, $targetFile);

        return sprintf('/media/catalog/product%s', $meida->file);
    }
}
