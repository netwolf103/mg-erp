<?php

namespace App\Command\Magento;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Traits\DatetimeTrait;
use App\Entity\SyncRecord;

use App\Traits\ConfigTrait;

/**
 * Command of magento sync.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class SyncCommand extends Command
{
	use DatetimeTrait;

	/**
	 * Entity Type.
	 * 
	 * @var string
	 */
	protected static $entityType;

	/**
	 * Command description.
	 * 
	 * @var string
	 */
	protected static $description = 'Magento data sync.';

	/**
	 * Api filter key.
	 * 
	 * @var string
	 */
	protected static $complexFilterKey;

    /**
     * Containe manager.
     * 
     * @var ContainerInterface
     */
    private $container;	

    /**
     * Api params from services.yaml
     * 
     * @var array
     */
	private $apiConfig;

	/**
	 * Message bus.
	 * 
	 * @var MessageBusInterface
	 */
	private $bus;

	/**
	 * SyncRecordRepository object.
	 * 
	 * @var SyncRecordRepository
	 */
	private $syncRecord;

	public function __construct(ContainerInterface $container, MessageBusInterface $bus)
	{
		$this->setContainer($container);
		$this->setBus($bus);

        try {
        	ConfigTrait::loadConfigs($this->getDoctrine()->getManager());
            $apiConfig = ConfigTrait::configMagentoApi();
        } catch (\Exception $e) {
            $apiConfig = [];
        }
        $this->setApiConfig($apiConfig);		

        parent::__construct();
	}

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription(static::$description)
            ->addArgument('api_username', InputArgument::OPTIONAL, 'The api username.', $this->getApiConfig('user'))
            ->addArgument('api_key', InputArgument::OPTIONAL, 'The api key.', $this->getApiConfig('key'))
            ->addArgument('api_url', InputArgument::OPTIONAL, 'The api domain.', $this->getApiConfig('url'))
        ;

        $this
        	->addOption('filter', null, InputOption::VALUE_OPTIONAL, 'Magento "complex_filter", Example --filter="field1=val1;field2=val2".', false)
        ;
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
	public function setContainer(ContainerInterface $container): self
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * Get ContainerInterface object.
	 * 
	 * @return [type] [description]
	 */
	public function getContainer(): ContainerInterface
	{
		return $this->container;
	}

	/**
	 * Set api config.
	 * 
	 * @param array $apiConfig
	 */
	public function setApiConfig(array $apiConfig): self
	{
		$this->apiConfig = $apiConfig;

		return $this;
	}

	/**
	 * Get api config value by key or all config values.
	 * 
	 * @param  string|null $key
	 * @return string|array
	 */
	public function getApiConfig(string $key = null)
	{
		return $key ? ($this->apiConfig[$key] ?? '') : $this->apiConfig;
	}

	/**
	 * Set SyncRecord object.
	 * 
	 * @param SyncRecord $syncRecord
	 */
	public function setSyncRecord(): self
	{
        $syncRecord = $this->getDoctrine()->getRepository(SyncRecord::class)->findOneBy(['entity_type' => static::$entityType]);
        if (is_null($syncRecord)) {
            $syncRecord = new SyncRecord;
            $syncRecord->setEntityType(static::$entityType);
            $syncRecord->setCreatedAt(new \DateTimeImmutable());
        }

		$this->syncRecord = $syncRecord;

		return $this;
	}

	/**
	 * Get SyncRecord object.
	 * 
	 * @return SyncRecord
	 */
	public function getSyncRecord(): SyncRecord
	{
		return $this->syncRecord;
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

	/**
	 * Return soap client.
	 * 
	 * @param  InterfaceSoap  $class
	 * @param  InputInterface $input
	 * @return InterfaceSoap
	 */
	protected function createClient(InputInterface $input, $class)
	{
		return new $class(
            $input->getArgument('api_url'), 
            $input->getArgument('api_username'), 
            $input->getArgument('api_key')
		);
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
     * Get api query filter.
     * 
     * @return array
     */
	protected function getFilter($filters = false): array
	{
		if ($filters) {
			$filters = explode(';', $filters);

			$filter = [];
			foreach ($filters as $value) {
				list($field, $value) = explode('=', $value);
				$filter['complex_filter'][] = [
					'key' => $field,
					'value' => [
						'key' => 'eq',
						'value' => $value
					]
				];
			}

            return $filter;
		}

        if ($lastEntityId = $this->getSyncRecord()->getLastEntityId()) {
            $filter = [
                'complex_filter' => [
                    [
                        'key' => static::$complexFilterKey,
                        'value' => [
                            'key' => 'gt',
                            'value' => $lastEntityId
                        ]
                    ]
                ]
            ];
        } else {
            $filter = [];
        }

        return $filter;		
	}
}