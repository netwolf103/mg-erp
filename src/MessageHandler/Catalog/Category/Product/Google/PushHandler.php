<?php
namespace App\MessageHandler\Catalog\Category\Product\Google;

use App\MessageHandler\MessageHandlerAbstract;

use App\Entity\Product\Google as GoogleEntity;
use App\Message\Catalog\Category\Product\Google\Push;
use App\Traits\ConfigTrait;

/**
 * Message handler for create google product.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PushHandler extends MessageHandlerAbstract
{
	const SCOPE = 'https://www.googleapis.com/auth/content';

	/**
	 * Push google product handler.
	 * 
	 * @param  Push $push
	 * @return void
	 */
    public function __invoke(Push $push)
    {
        $id = $push->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->clear();

        $googleEntity = $entityManager->getRepository(GoogleEntity::class)->find($id);

        if (!$googleEntity) {
            return;
        }

        ConfigTrait::loadConfigs($entityManager);
        $merchant_id = ConfigTrait::configGoogleMerchantsId();
        $authConfig  = ConfigTrait::configGoogleAuth();

        if (!ConfigTrait::configGoogleMerchantsEnabled()) {
        	return false;
        }

        $client = new \Google_Client();
        $client->addScope(self::SCOPE);
        $client->setAuthConfig($authConfig);

        $service = new \Google_Service_ShoppingContent($client);

        try {
	        $results = $service->products->insert($merchant_id, $this->getProduct($googleEntity)); 

	        $this->success(
	            sprintf("Product id #%s, Done\r\n", $googleEntity->getId())
	        );        	
        } catch (\Exception $e) {
        	$error = json_decode($e->getMessage());
        	$this->error($error->error->message ?? $e->getMessage());
        }                 
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
            $_salePriceEffectiveDate = sprintf('%s/%s', date(DATE_ISO8601), $google->getGSalePriceEffectiveDate()->format(DATE_ISO8601));			
			$product->setAvailabilityDate($_salePriceEffectiveDate);
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