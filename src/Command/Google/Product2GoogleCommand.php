<?php
namespace App\Command\Google;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Entity\Product;
use App\Message\Catalog\Category\Product\Google;

/**
 * Command of create google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Product2GoogleCommand extends Command
{
    /**
     * Command name.
     *
     * @var string
     */    
    protected static $defaultName = 'app:google:create-product';

    /**
     * Containe manager.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Message bus.
     *
     * @var MessageBusInterface
     */
    private $bus;

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
            ->addArgument('sku', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sku = $input->getArgument('sku');

        $query = [];

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

    /**
     * Dispatches a message to the bus.
     *
     * @param object|Envelope $message The message or the message pre-wrapped in an envelope
     *
     * @final
     */
    protected function dispatchMessage($message)
    {
        return $this->bus->dispatch($message);
    }

    /**
     * Set MessageBusInterface object.
     *
     * @param MessageBusInterface $container
     */
    public function setBus(MessageBusInterface $bus): self
    {
        $this->bus = $bus;

        return $this;
    }

    /**
     * Get MessageBusInterface object.
     *
     * @return [type] [description]
     */
    public function getBus(): MessageBusInterface
    {
        return $this->bus;
    }  

    /**
     * Set ContainerInterface object.
     *
     * @param ContainerInterface $container
     */
    protected function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get ContainerInterface object.
     *
     * @return [type] [description]
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Get ManagerRegistry object.
     *
     * @return ManagerRegistry
     */
    protected function getDoctrine(): ManagerRegistry
    {
        return $this->container->get('doctrine');
    }    
}
