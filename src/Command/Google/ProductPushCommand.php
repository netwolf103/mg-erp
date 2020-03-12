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
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Product\Google as GoogleEntity;
use App\Traits\ConfigTrait;

/**
 * Command of push product to google.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductPushCommand extends Command
{
    const SCOPE = 'https://www.googleapis.com/auth/content';

    /**
     * Api config path.
     * 
     * @var string
     */
    protected static $apiConfigPath = 'Google';

    /**
     * Command name.
     * 
     * @var string
     */
    protected static $defaultName = 'app:google:push-product';

    /**
     * Google merchant id.
     * 
     * @var array
     */
    private $merchantId;

    /**
     * The google shopping content service.
     * 
     * @var Google_Service_ShoppingContent
     */
    private $service;

    /**
     * Containe manager.
     * 
     * @var ContainerInterface
     */
    private $container;     
  

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        ConfigTrait::loadConfigs($em);

        $this->merchantId = ConfigTrait::configGoogleMerchantsId();
        $authConfig  = ConfigTrait::configGoogleAuth();   

        if (isset($authConfig['type']) && $authConfig['type'] == 'service_account') {
            $client = new \Google_Client();
            $client->addScope(self::SCOPE);
            $client->setAuthConfig($authConfig);

            $this->service = new \Google_Service_ShoppingContent($client);
        }

        $this->setContainer($container);

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Push local product to google shopping content.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!ConfigTrait::configGoogleMerchantsEnabled()) {
            throw new \Exception('Please enable google merchants.');
            
        }

        $products = $this->getDoctrine()->getRepository(GoogleEntity::class)->findAll();
        $total = count($products);
        $adder = 0;
        $io->writeln(sprintf('Total %d products.', $total));
        foreach ($products as $product) {
            try {
                $io->writeln(sprintf('%s # %d/%d', $product->getGOfferId(), $total, ++$adder));
                $response = $this->service->products->insert($this->merchantId, $this->getProduct($product));
            } catch (\Exception $e) {
                $error = json_decode($e->getMessage());
                $message = $error->error->message ?? $e->getMessage();
                $io->error(sprintf('%s -> %s', $product->getParent()->getSku(), $message));      
            }
        }

        $io->success('Products successfully push done.');
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

    /**
     * Return google product.
     * 
     * @param  GoogleEntity $google
     * @param  string       $kind
     * @param  string       $contentLanguage
     * @param  string       $targetCountry
     * @param  string       $channel
     * @return Google_Service_ShoppingContent_Product
     */
    private function getProduct(GoogleEntity $google, string $kind = 'content#product', string $contentLanguage = 'en', string $targetCountry = 'US', string $channel = 'online'): \Google_Service_ShoppingContent_Product
    {
        $product = new \Google_Service_ShoppingContent_Product();
        $product->setKind($kind);
        $product->setContentLanguage($contentLanguage);
        $product->setTargetCountry($targetCountry);
        $product->setChannel($channel);

        if ($google->getGOfferId()) {
            $product->setOfferId($google->getGOfferId());
        }

        if ($google->getGTitle()) {
            $product->setTitle($google->getGTitle());
        }   

        if ($google->getGDescription()) {
            $product->setDescription($google->getGDescription());
        }           
                        
        if ($google->getGLink()) {
            $product->setLink($google->getGLink());
        }

        if ($google->getGImageLink()) {
            $product->setImageLink($google->getGImageLink());
        }

        $additionalImageLinks = $google->getGAdditionalImageLink() ?: '';
        if (!empty($additionalImageLinks)) {
            $additionalImageLinks = json_decode($additionalImageLinks);

            $product->setAdditionalImageLinks($additionalImageLinks);
        }

        if ($google->getGAvailability()) {
            $product->setAvailability($google->getGAvailability());
        }

        if ($google->getGAvailabilityDate()) {
            $product->setAvailabilityDate($google->getGAvailabilityDate());
        }

        if ($google->getGCostOfGoodsSold()) {
            list($price, $currency) = explode(' ', $google->getGCostOfGoodsSold());
            $price = new \Google_Service_ShoppingContent_Price();
            $price->setValue($price);
            $price->setCurrency($currency);

            $product->setCostOfGoodsSold($price);
        }

        if ($google->getGExpirationDate()) {
            $product->setExpirationDate($google->getGExpirationDate());
        }

        if ($google->getGPrice()) {
            list($value, $currency) = explode(' ', $google->getGPrice());
            $price = new \Google_Service_ShoppingContent_Price();
            $price->setValue($value);
            $price->setCurrency($currency);

            $product->setPrice($price);
        }

        if ($google->getGSalePrice()) {
            list($value, $currency) = explode(' ', $google->getGSalePrice());
            $price = new \Google_Service_ShoppingContent_Price();
            $price->setValue($value);
            $price->setCurrency($currency);

            $product->setSalePrice($price);
        }       

        if ($google->getGSalePriceEffectiveDate()) {
            $_salePriceEffectiveDate = sprintf('%s/%s', date(DATE_ISO8601), $google->getGSalePriceEffectiveDate()->format(DATE_ISO8601));
            $product->setSalePriceEffectiveDate($_salePriceEffectiveDate);
        }
        if ($google->getGUnitPriceMeasure()) {
            list($value, $unit) = explode(' ', $google->getGUnitPriceMeasure());
            $productUnitPricingMeasure = new \Google_Service_ShoppingContent_ProductUnitPricingMeasure();
            $productUnitPricingMeasure->setValue($value);
            $productUnitPricingMeasure->setUnit($unit);

            $product->setUnitPricingMeasure($productUnitPricingMeasure);
        }

        if ($google->getGUnitPricingBaseMeasure()) {
            list($value, $unit) = explode(' ', $google->getGUnitPricingBaseMeasure());
            $productUnitPricingBaseMeasure = new \Google_Service_ShoppingContent_ProductUnitPricingBaseMeasure();
            $productUnitPricingBaseMeasure->setValue($value);
            $productUnitPricingBaseMeasure->setUnit($unit);

            $product->setUnitPricingBaseMeasure($productUnitPricingBaseMeasure);
        }       

        if ($google->getGInstallment()) {
            list($months, $amount) = explode(' ', $google->getGInstallment());
            $installment = new \Google_Service_ShoppingContent_Installment();
            $installment->setMonths($months);
            $installment->setAmount($amount);

            $product->setInstallment($google->getGInstallment());
        }

        if ($google->getGGoogleProductCategoryId()) {
            $product->setGoogleProductCategory($google->getGGoogleProductCategoryId());
        }

        if ($google->getGProductType()) {
            $product->setProductTypes($google->getGProductType());
        }

        if ($google->getGGtin()) {
            $product->setGtin($google->getGGtin());
        }

        if ($google->getGMpn()) {
            $product->setMpn($google->getGMpn());
        }

        if ($google->getGBrand()) {
            $product->setBrand($google->getGBrand());
        }

        $product->setIdentifierExists($google->getGIdentifierExists());

        if ($google->getGCondition()) {
            $product->setCondition($google->getGCondition());
        }

        if ($google->getGAdult()) {
            $product->setAdult($google->getGAdult());
        }   
        
        if ($google->getGMultipack()) {
            $product->setMultipack($google->getGMultipack());
        }

        $product->setIsBundle($google->getGIsBundle());         

        if ($google->getGEnergyEfficiencyClass()) {
            $product->setEnergyEfficiencyClass($google->getGEnergyEfficiencyClass());
        }

        if ($google->getGMinEnergyEfficiencyClass()) {
            $product->setMinEnergyEfficiencyClass($google->getGMinEnergyEfficiencyClass());
        }

        if ($google->getGMaxEnergyEfficiencyClass()) {
            $product->setMaxEnergyEfficiencyClass($google->getGMaxEnergyEfficiencyClass());
        }       

        if ($google->getGAgeGroup()) {
            $product->setAgeGroup($google->getGAgeGroup());
        }       

        if ($google->getGColor()) {
            $product->setColor($google->getGColor());
        }

        if ($google->getGGender()) {
            $product->setGender($google->getGGender());
        }   

        if ($google->getGMaterial()) {
            $product->setMaterial($google->getGMaterial());
        }

        if ($google->getGPattern()) {
            $product->setPattern($google->getGPattern());
        }

        if ($google->getGSize()) {
            $sizes = json_decode($google->getGSize());
            $product->setSizes($sizes);
        }

        if ($google->getGSizeType()) {
            $product->setSizeType($google->getGSizeType());
        }

        if ($google->getGSizeSystem()) {
            $product->setSizeSystem($google->getGSizeSystem());
        }                               

        if ($google->getGItemGroupId()) {
            $product->setItemGroupId($google->getGItemGroupId());
        }

        if ($google->getGAdsRedirect()) {
            $product->setAdsRedirect($google->getGAdsRedirect());
        }

        if ($google->getGCustomLabel0()) {
            $product->setCustomLabel0($google->getGCustomLabel0());
        }

        if ($google->getGCustomLabel1()) {
            $product->setCustomLabel1($google->getGCustomLabel1());
        }

        if ($google->getGCustomLabel2()) {
            $product->setCustomLabel2($google->getGCustomLabel2());
        }

        if ($google->getGCustomLabel3()) {
            $product->setCustomLabel3($google->getGCustomLabel3());
        }

        if ($google->getGCustomLabel4()) {
            $product->setCustomLabel4($google->getGCustomLabel4());
        }

        if ($google->getGPromotionId()) {
            $product->setPromotionIds($google->getGPromotionId());
        }

        if ($google->getGExcludedDestination()) {
            $product->setExcludedDestinations($google->getGExcludedDestination());
        }                                                               

        if ($google->getGIncludedDestination()) {
            $product->setIncludedDestinations($google->getGIncludedDestination());
        }

        if ($google->getGShipping()) {
            $product->setShipping($google->getGShipping());
        }       

        if ($google->getGShippingLabel()) {
            $product->setShippingLabel($google->getGShippingLabel());
        }

        if ($google->getGShippingWeight()) {
            list($value, $unit) = explode(' ', $google->getGShippingWeight());
            $productShippingWeight = new \Google_Service_ShoppingContent_ProductShippingWeight();
            $productShippingWeight->setValue($value);
            $productShippingWeight->setUnit($unit);

            $product->setShippingWeight($productShippingWeight);
        }

        if ($google->getGShippingLength()) {
            list($value, $unit) = explode(' ', $google->getGShippingLength());
            $productShippingDimension = new \Google_Service_ShoppingContent_ProductShippingDimension();
            $productShippingDimension->setValue($value);
            $productShippingDimension->setUnit($unit);

            $product->setShippingLength($productShippingDimension);
        }

        if ($google->getGShippingWidth()) {
            list($value, $unit) = explode(' ', $google->getGShippingWidth());
            $productShippingDimension = new \Google_Service_ShoppingContent_ProductShippingDimension();
            $productShippingDimension->setValue($value);
            $productShippingDimension->setUnit($unit);
                        
            $product->setShippingWidth($productShippingDimension);
        }   
        
        if ($google->getGShippingHeight()) {
            list($value, $unit) = explode(' ', $google->getGShippingHeight());
            $productShippingDimension = new \Google_Service_ShoppingContent_ProductShippingDimension();
            $productShippingDimension->setValue($value);
            $productShippingDimension->setUnit($unit);
                        
            $product->setShippingHeight($productShippingDimension);
        }   
        
        if ($google->getGTransitTimeLabel()) {
            $product->setTransitTimeLabel($google->getGTransitTimeLabel());
        }   
        
        if ($google->getGMaxHandlingTime()) {
            $product->setMaxHandlingTime($google->getGMaxHandlingTime());
        }   
        
        if ($google->getGMinHandlingTime()) {
            $product->setMinHandlingTime($google->getGMinHandlingTime());
        }

        if ($google->getGTax()) {
            $product->setTaxes($google->getGTax());
        }                               

        if ($google->getGTaxCategory()) {
            $product->setTaxCategory($google->getGTaxCategory());
        }

        return $product;                                        
    }           
}
