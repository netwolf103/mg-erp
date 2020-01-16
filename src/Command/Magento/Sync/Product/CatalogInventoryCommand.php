<?php

namespace App\Command\Magento\Sync\Product;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

use App\Api\Magento1x\Soap\Catalog\CatalogInventorySoap;

use App\Command\Magento\SyncCommand;

use App\Entity\Product;
use App\Entity\Product\CatalogInventory;

/**
 * Command of sync product inventory.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CatalogInventoryCommand extends SyncCommand
{
    protected static $entityType = 'magento.catalog.catalog.inventory'; 
    protected static $defaultName = 'app:magento:sync-catalog-inventory';
    protected static $title =  'Get Magento Catalog Inventory';
    protected static $description =  'Sync catalog inventory.';
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

        $client = $this->createClient($input, CatalogInventorySoap::class);

        $products = $entityManager->getRepository(Product::class)->findAll();
        $total = count($products);
        $adder = 0;
        $io->writeln(sprintf('Total %d products.', $total));

        foreach ($products as $product) {
            $io->section(sprintf('# %s', $product->getSku()));

            $inventory = $client->callCatalogInventoryStockItemList([$product->getSku()]);
            $inventory = array_shift($inventory);
            if (!$inventory) {
                continue;
            }

            $catalogInventoryEntity = $entityManager->getRepository(CatalogInventory::class)->findOneBy(['sku' => $inventory->sku]);
            if (!$catalogInventoryEntity) {
                $catalogInventoryEntity = new CatalogInventory;
            }
            
            $catalogInventoryEntity->setProduct($product);
            $catalogInventoryEntity->setSku($inventory->sku);
            $catalogInventoryEntity->setQty($inventory->qty);
            $catalogInventoryEntity->setIsInStock($inventory->is_in_stock);

            $entityManager->persist($catalogInventoryEntity);
            $entityManager->flush();
        }

        $io->success('Products successfully synced.');
    }
}
