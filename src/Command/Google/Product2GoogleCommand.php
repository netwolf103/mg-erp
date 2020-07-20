<?php
namespace App\Command\Google;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Command\AbstractCommand;
use App\Entity\Product;
use App\Message\Catalog\Category\Product\Google;

/**
 * Command of create google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Product2GoogleCommand extends AbstractCommand
{
    /**
     * Command name.
     *
     * @var string
     */    
    protected static $defaultName = 'app:google:create-product';

    public function __construct(ContainerInterface $container, MessageBusInterface $bus)
    {
        $this->setContainer($container);
        $this->setBus($bus);

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Local products to google products')
            ->addArgument('sku', InputArgument::OPTIONAL, 'Product sku')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sku = $input->getArgument('sku');

        $query = [
            'catalogInventory' => 1
        ];

        if ($sku) {
            $query['sku'] = $sku;
        }

        $paginator = $this->getDoctrine()->getRepository(Product::class)->getAll($query);
        
        foreach ($paginator['results'] as $_product) {
            $this->dispatchMessage(new Google($_product->getId()));
        }

        $io->success('The product joined task queue.');

        return 0;
    }   
}
