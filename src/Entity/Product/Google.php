<?php

namespace App\Entity\Product;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\GoogleRepository")
 * @ORM\Table(name="product_google")
 */
class Google
{
    /**
     * Age group values.
     */
    const AGE_GROUP_NEWBORN = 'newborn';
    const AGE_GROUP_INFANT  = 'infant';
    const AGE_GROUP_TODDLER = 'toddler';
    const AGE_GROUP_KID     = 'kid';
    const AGE_GROUP_ADULT   = 'adult';

    protected static $_ageGroups;

    /**
     * Condition values.
     */
    const CONDITION_NEW         = 'new';
    const CONDITION_REFURBISHED = 'refurbished';
    const CONDITION_USED        = 'used';

    protected static $_conditions;

    /**
     * Gender values.
     */
    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_UNISEX = 'unisex';

    protected static $_genders;

    /**
     * Availability values.
     */
    const AVAILABILITY_IN_STOCK     = 'in stock';
    const AVAILABILITY_OUT_OF_STOCK = 'out of stock';
    const AVAILABILITY_PREORDER     = 'preorder';

    protected static $_availabilitys;             

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="google")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $g_offer_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $g_title;

    /**
     * @ORM\Column(type="text")
     */
    private $g_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $g_link;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $g_image_link;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $g_additional_image_link;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $g_availability;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $g_availability_date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_cost_of_goods_sold;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $g_expiration_date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $g_price;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_sale_price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $g_sale_price_effective_date;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_unit_price_measure;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_unit_pricing_base_measure;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_installment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $g_google_product_category_id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $g_product_type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $g_brand;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_gtin;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_mpn;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $g_identifier_exists;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $g_condition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $g_adult;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_multipack;

    /**
     * @ORM\Column(type="boolean")
     */
    private $g_is_bundle;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_energy_efficiency_class;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_min_energy_efficiency_class;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_max_energy_efficiency_class;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $g_age_group;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $g_color;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $g_gender;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $g_material;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_pattern;

    /**
     * @ORM\Column(type="text")
     */
    private $g_size;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_size_type;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $g_size_system;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $g_item_group_id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $g_ads_redirect;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_custom_label_0;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_custom_label_1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_custom_label_2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_custom_label_3;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_custom_label_4;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_promotion_id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_excluded_destination;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $g_included_destination;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $g_shipping;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_shipping_label;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $g_shipping_weight;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_shipping_length;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_shipping_width;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_shipping_height;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_transit_time_label;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $g_max_handling_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $g_min_handling_time;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $g_tax;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $g_tax_category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?Product
    {
        return $this->parent;
    }

    public function setParent(?Product $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getGOfferId(): ?string
    {
        return $this->g_offer_id;
    }

    public function setGOfferId(string $g_offer_id): self
    {
        $this->g_offer_id = $g_offer_id;

        return $this;
    }

    public function getGTitle(): ?string
    {
        return $this->g_title;
    }

    public function setGTitle(string $g_title): self
    {
        $this->g_title = $g_title;

        return $this;
    }

    public function getGDescription(): ?string
    {
        return $this->g_description;
    }

    public function setGDescription(string $g_description): self
    {
        $this->g_description = $g_description;

        return $this;
    }

    public function getGLink(): ?string
    {
        return $this->g_link;
    }

    public function setGLink(string $g_link): self
    {
        $this->g_link = $g_link;

        return $this;
    }

    public function getGImageLink(): ?string
    {
        return $this->g_image_link;
    }

    public function setGImageLink(string $g_image_link): self
    {
        $this->g_image_link = $g_image_link;

        return $this;
    }

    public function getGAdditionalImageLink(): ?string
    {
        return $this->g_additional_image_link;
    }

    public function setGAdditionalImageLink(?string $g_additional_image_link): self
    {
        $this->g_additional_image_link = $g_additional_image_link;

        return $this;
    }

    public function getGAvailability(): ?string
    {
        return $this->g_availability;
    }

    public function setGAvailability(string $g_availability): self
    {
        $this->g_availability = $g_availability;

        return $this;
    }

    public function getGAvailabilityDate(): ?\DateTimeInterface
    {
        return $this->g_availability_date;
    }

    public function setGAvailabilityDate(?\DateTimeInterface $g_availability_date): self
    {
        $this->g_availability_date = $g_availability_date;

        return $this;
    }

    public function getGCostOfGoodsSold(): ?string
    {
        return $this->g_cost_of_goods_sold;
    }

    public function setGCostOfGoodsSold(?string $g_cost_of_goods_sold): self
    {
        $this->g_cost_of_goods_sold = $g_cost_of_goods_sold;

        return $this;
    }

    public function getGExpirationDate(): ?\DateTimeInterface
    {
        return $this->g_expiration_date;
    }

    public function setGExpirationDate(?\DateTimeInterface $g_expiration_date): self
    {
        $this->g_expiration_date = $g_expiration_date;

        return $this;
    }

    public function getGPrice(): ?string
    {
        return $this->g_price;
    }

    public function setGPrice(string $g_price): self
    {
        $this->g_price = $g_price;

        return $this;
    }

    public function getGSalePrice(): ?string
    {
        return $this->g_sale_price;
    }

    public function setGSalePrice(?string $g_sale_price): self
    {
        $this->g_sale_price = $g_sale_price;

        return $this;
    }

    public function getGSalePriceEffectiveDate(): ?\DateTimeInterface
    {
        return $this->g_sale_price_effective_date;
    }

    public function setGSalePriceEffectiveDate(?\DateTimeInterface $g_sale_price_effective_date): self
    {
        $this->g_sale_price_effective_date = $g_sale_price_effective_date;

        return $this;
    }

    public function getGUnitPriceMeasure(): ?string
    {
        return $this->g_unit_price_measure;
    }

    public function setGUnitPriceMeasure(?string $g_unit_price_measure): self
    {
        $this->g_unit_price_measure = $g_unit_price_measure;

        return $this;
    }

    public function getGUnitPricingBaseMeasure(): ?string
    {
        return $this->g_unit_pricing_base_measure;
    }

    public function setGUnitPricingBaseMeasure(?string $g_unit_pricing_base_measure): self
    {
        $this->g_unit_pricing_base_measure = $g_unit_pricing_base_measure;

        return $this;
    }

    public function getGInstallment(): ?string
    {
        return $this->g_installment;
    }

    public function setGInstallment(?string $g_installment): self
    {
        $this->g_installment = $g_installment;

        return $this;
    }

    public function getGGoogleProductCategoryId(): ?int
    {
        return $this->g_google_product_category_id;
    }

    public function setGGoogleProductCategoryId(?int $g_google_product_category_id): self
    {
        $this->g_google_product_category_id = $g_google_product_category_id;

        return $this;
    }

    public function getGProductType(): ?string
    {
        return $this->g_product_type;
    }

    public function setGProductType(?string $g_product_type): self
    {
        $this->g_product_type = $g_product_type;

        return $this;
    }

    public function getGBrand(): ?string
    {
        return $this->g_brand;
    }

    public function setGBrand(string $g_brand): self
    {
        $this->g_brand = $g_brand;

        return $this;
    }

    public function getGGtin(): ?string
    {
        return $this->g_gtin;
    }

    public function setGGtin(?string $g_gtin): self
    {
        $this->g_gtin = $g_gtin;

        return $this;
    }

    public function getGMpn(): ?string
    {
        return $this->g_mpn;
    }

    public function setGMpn(?string $g_mpn): self
    {
        $this->g_mpn = $g_mpn;

        return $this;
    }

    public function getGIdentifierExists(): ?bool
    {
        return $this->g_identifier_exists;
    }

    public function setGIdentifierExists(?bool $g_identifier_exists): self
    {
        $this->g_identifier_exists = $g_identifier_exists;

        return $this;
    }

    public function getGCondition(): ?string
    {
        return $this->g_condition;
    }

    public function setGCondition(string $g_condition): self
    {
        $this->g_condition = $g_condition;

        return $this;
    }

    public function getGAdult(): ?bool
    {
        return $this->g_adult;
    }

    public function setGAdult(bool $g_adult): self
    {
        $this->g_adult = $g_adult;

        return $this;
    }

    public function getGMultipack(): ?string
    {
        return $this->g_multipack;
    }

    public function setGMultipack(?string $g_multipack): self
    {
        $this->g_multipack = $g_multipack;

        return $this;
    }

    public function getGIsBundle(): ?bool
    {
        return $this->g_is_bundle;
    }

    public function setGIsBundle(bool $g_is_bundle): self
    {
        $this->g_is_bundle = $g_is_bundle;

        return $this;
    }

    public function getGEnergyEfficiencyClass(): ?string
    {
        return $this->g_energy_efficiency_class;
    }

    public function setGEnergyEfficiencyClass(?string $g_energy_efficiency_class): self
    {
        $this->g_energy_efficiency_class = $g_energy_efficiency_class;

        return $this;
    }

    public function getGMinEnergyEfficiencyClass(): ?string
    {
        return $this->g_min_energy_efficiency_class;
    }

    public function setGMinEnergyEfficiencyClass(?string $g_min_energy_efficiency_class): self
    {
        $this->g_min_energy_efficiency_class = $g_min_energy_efficiency_class;

        return $this;
    }

    public function getGMaxEnergyEfficiencyClass(): ?string
    {
        return $this->g_max_energy_efficiency_class;
    }

    public function setGMaxEnergyEfficiencyClass(?string $g_max_energy_efficiency_class): self
    {
        $this->g_max_energy_efficiency_class = $g_max_energy_efficiency_class;

        return $this;
    }

    public function getGAgeGroup(): ?string
    {
        return $this->g_age_group;
    }

    public function setGAgeGroup(string $g_age_group): self
    {
        $this->g_age_group = $g_age_group;

        return $this;
    }

    public function getGColor(): ?string
    {
        return $this->g_color;
    }

    public function setGColor(string $g_color): self
    {
        $this->g_color = $g_color;

        return $this;
    }

    public function getGGender(): ?string
    {
        return $this->g_gender;
    }

    public function setGGender(string $g_gender): self
    {
        $this->g_gender = $g_gender;

        return $this;
    }

    public function getGMaterial(): ?string
    {
        return $this->g_material;
    }

    public function setGMaterial(string $g_material): self
    {
        $this->g_material = $g_material;

        return $this;
    }

    public function getGPattern(): ?string
    {
        return $this->g_pattern;
    }

    public function setGPattern(?string $g_pattern): self
    {
        $this->g_pattern = $g_pattern;

        return $this;
    }

    public function getGSize(): ?string
    {
        return $this->g_size;
    }

    public function setGSize(string $g_size): self
    {
        $this->g_size = $g_size;

        return $this;
    }

    public function getGSizeType(): ?string
    {
        return $this->g_size_type;
    }

    public function setGSizeType(?string $g_size_type): self
    {
        $this->g_size_type = $g_size_type;

        return $this;
    }

    public function getGSizeSystem(): ?string
    {
        return $this->g_size_system;
    }

    public function setGSizeSystem(?string $g_size_system): self
    {
        $this->g_size_system = $g_size_system;

        return $this;
    }

    public function getGItemGroupId(): ?string
    {
        return $this->g_item_group_id;
    }

    public function setGItemGroupId(string $g_item_group_id): self
    {
        $this->g_item_group_id = $g_item_group_id;

        return $this;
    }

    public function getGAdsRedirect(): ?string
    {
        return $this->g_ads_redirect;
    }

    public function setGAdsRedirect(?string $g_ads_redirect): self
    {
        $this->g_ads_redirect = $g_ads_redirect;

        return $this;
    }

    public function getGCustomLabel0(): ?string
    {
        return $this->g_custom_label_0;
    }

    public function setGCustomLabel0(?string $g_custom_label_0): self
    {
        $this->g_custom_label_0 = $g_custom_label_0;

        return $this;
    }

    public function getGCustomLabel1(): ?string
    {
        return $this->g_custom_label_1;
    }

    public function setGCustomLabel1(?string $g_custom_label_1): self
    {
        $this->g_custom_label_1 = $g_custom_label_1;

        return $this;
    }

    public function getGCustomLabel2(): ?string
    {
        return $this->g_custom_label_2;
    }

    public function setGCustomLabel2(?string $g_custom_label_2): self
    {
        $this->g_custom_label_2 = $g_custom_label_2;

        return $this;
    }

    public function getGCustomLabel3(): ?string
    {
        return $this->g_custom_label_3;
    }

    public function setGCustomLabel3(?string $g_custom_label_3): self
    {
        $this->g_custom_label_3 = $g_custom_label_3;

        return $this;
    }

    public function getGCustomLabel4(): ?string
    {
        return $this->g_custom_label_4;
    }

    public function setGCustomLabel4(?string $g_custom_label_4): self
    {
        $this->g_custom_label_4 = $g_custom_label_4;

        return $this;
    }

    public function getGPromotionId(): ?string
    {
        return $this->g_promotion_id;
    }

    public function setGPromotionId(?string $g_promotion_id): self
    {
        $this->g_promotion_id = $g_promotion_id;

        return $this;
    }

    public function getGExcludedDestination(): ?string
    {
        return $this->g_excluded_destination;
    }

    public function setGExcludedDestination(?string $g_excluded_destination): self
    {
        $this->g_excluded_destination = $g_excluded_destination;

        return $this;
    }

    public function getGIncludedDestination(): ?string
    {
        return $this->g_included_destination;
    }

    public function setGIncludedDestination(?string $g_included_destination): self
    {
        $this->g_included_destination = $g_included_destination;

        return $this;
    }

    public function getGShipping(): ?string
    {
        return $this->g_shipping;
    }

    public function setGShipping(?string $g_shipping): self
    {
        $this->g_shipping = $g_shipping;

        return $this;
    }

    public function getGShippingLabel(): ?string
    {
        return $this->g_shipping_label;
    }

    public function setGShippingLabel(?string $g_shipping_label): self
    {
        $this->g_shipping_label = $g_shipping_label;

        return $this;
    }

    public function getGShippingWeight(): ?string
    {
        return $this->g_shipping_weight;
    }

    public function setGShippingWeight(?string $g_shipping_weight): self
    {
        $this->g_shipping_weight = $g_shipping_weight;

        return $this;
    }

    public function getGShippingLength(): ?string
    {
        return $this->g_shipping_length;
    }

    public function setGShippingLength(?string $g_shipping_length): self
    {
        $this->g_shipping_length = $g_shipping_length;

        return $this;
    }

    public function getGShippingWidth(): ?string
    {
        return $this->g_shipping_width;
    }

    public function setGShippingWidth(?string $g_shipping_width): self
    {
        $this->g_shipping_width = $g_shipping_width;

        return $this;
    }

    public function getGShippingHeight(): ?string
    {
        return $this->g_shipping_height;
    }

    public function setGShippingHeight(?string $g_shipping_height): self
    {
        $this->g_shipping_height = $g_shipping_height;

        return $this;
    }

    public function getGTransitTimeLabel(): ?string
    {
        return $this->g_transit_time_label;
    }

    public function setGTransitTimeLabel(?string $g_transit_time_label): self
    {
        $this->g_transit_time_label = $g_transit_time_label;

        return $this;
    }

    public function getGMaxHandlingTime(): ?int
    {
        return $this->g_max_handling_time;
    }

    public function setGMaxHandlingTime(?int $g_max_handling_time): self
    {
        $this->g_max_handling_time = $g_max_handling_time;

        return $this;
    }

    public function getGMinHandlingTime(): ?int
    {
        return $this->g_min_handling_time;
    }

    public function setGMinHandlingTime(?int $g_min_handling_time): self
    {
        $this->g_min_handling_time = $g_min_handling_time;

        return $this;
    }

    public function getGTax(): ?string
    {
        return $this->g_tax;
    }

    public function setGTax(string $g_tax): self
    {
        $this->g_tax = $g_tax;

        return $this;
    }

    public function getGTaxCategory(): ?string
    {
        return $this->g_tax_category;
    }

    public function setGTaxCategory(?string $g_tax_category): self
    {
        $this->g_tax_category = $g_tax_category;

        return $this;
    }

    public function getGId(string $channel = 'online', string $contentLanguage = 'en', string $targetCountry = 'US'): string
    {
        return sprintf('%s:%s:%s:%s', $channel, $contentLanguage, $targetCountry, $this->g_offer_id);
    }

    public static function getAgeGroupList(): array
    {
        if (is_null(static::$_ageGroups)) {
            static::$_ageGroups = array(
                static::AGE_GROUP_NEWBORN   => static::AGE_GROUP_NEWBORN,
                static::AGE_GROUP_INFANT    => static::AGE_GROUP_INFANT,
                static::AGE_GROUP_TODDLER   => static::AGE_GROUP_TODDLER,
                static::AGE_GROUP_KID       => static::AGE_GROUP_KID,
                static::AGE_GROUP_ADULT     => static::AGE_GROUP_ADULT,
            );
        }
        return static::$_ageGroups;
    }

    public static function getConditionList(): array
    {
        if (is_null(static::$_conditions)) {
            static::$_conditions = array(
                static::CONDITION_NEW           => static::CONDITION_NEW,
                static::CONDITION_REFURBISHED   => static::CONDITION_REFURBISHED,
                static::CONDITION_USED          => static::CONDITION_USED,
            );
        }
        return static::$_conditions;
    } 

    public static function getGenderList(): array
    {
        if (is_null(static::$_genders)) {
            static::$_genders = array(
                static::GENDER_MALE     => static::GENDER_MALE,
                static::GENDER_FEMALE   => static::GENDER_FEMALE,
                static::GENDER_UNISEX   => static::GENDER_UNISEX,
            );
        }
        return static::$_genders;
    }

    public static function getAvailabilityList(): array
    {
        if (is_null(static::$_availabilitys)) {
            static::$_availabilitys = array(
                static::AVAILABILITY_IN_STOCK       => static::AVAILABILITY_IN_STOCK,
                static::AVAILABILITY_OUT_OF_STOCK   => static::AVAILABILITY_OUT_OF_STOCK,
                static::AVAILABILITY_PREORDER       => static::AVAILABILITY_PREORDER,
            );
        }
        return static::$_availabilitys;
    }        
}
