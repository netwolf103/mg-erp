<?php

namespace App\Form\Product;

use App\Entity\Product\Google;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Form type class of Google.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GoogleType extends AbstractType
{
    private $parameter;
    private $translator;

    public function __construct(ParameterBagInterface $parameter, TranslatorInterface $translator)
    {
        $this->parameter = $parameter;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */       
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $googleParameters = $this->parameter->get('Google');
        $helpTaxonomy = $googleParameters['Merchants']['help']['url']['taxonomy'] ?? '';

        $builder
            ->add('g_offer_id', null, [
                'label' => 'Offer Id'
            ])
            ->add('g_title', null, [
                'label' => 'GTitle'
            ])
            ->add('g_description', null, [
                'label' => 'GDescription'
            ])
            ->add('g_link', null, [
                'label' => 'GLink'
            ])
            ->add('g_image_link', null, [
                'label' => 'GImage Link'
            ])
            ->add('g_additional_image_link', null, [
                'label' => 'GAdditional Image Link'
            ])
            ->add('g_availability', ChoiceType::class, [
                'label' => 'GAvailability',
                'choices' => Google::getAvailabilityList()
            ])
            ->add('g_availability_date', null, [
                'label' => 'GAvailability Date'
            ])
            ->add('g_cost_of_goods_sold', null, [
                'label' => 'GCost Of Goods Sold'
            ])
            ->add('g_expiration_date', null, [
                'label' => 'GExpiration Date'
            ])
            ->add('g_price', null, [
                'label' => 'GPrice'
            ])
            ->add('g_sale_price', null, [
                'label' => 'GSale Price'
            ])
            ->add('g_sale_price_effective_date', null, [
                'label' => 'GSale Price Effective Date'
            ])
            ->add('g_unit_price_measure', null, [
                'label' => 'GUnit Price Measure'
            ])
            ->add('g_unit_pricing_base_measure', null, [
                'label' => 'GUnit Pricing Base Measure'
            ])
            ->add('g_installment', null, [
                'label' => 'GInstallment'
            ])
            ->add('g_google_product_category_id', null, [
                'label' => 'GGoogle Product Category Id',
                'help_html' => true,
                'help' => $this->translator->trans('Reference Link: <a href=":link" target="_blank">:link</a>', [':link' => $helpTaxonomy])
            ])
            ->add('g_product_type', null, [
                'label' => 'GProduct Type'
            ])
            ->add('g_brand', null, [
                'label' => 'Brand'
            ])
            ->add('g_gtin', null, [
                'label' => 'Gtin'
            ])
            ->add('g_mpn', null, [
                'label' => 'MPN'
            ])
            ->add('g_identifier_exists', null, [
                'label' => 'GIdentifier Exists'
            ])
            ->add('g_condition', ChoiceType::class, [
                'label' => 'GCondition',
                'choices' => Google::getConditionList()
            ])
            ->add('g_adult', null, [
                'label' => 'GAdult'
            ])
            ->add('g_multipack', null, [
                'label' => 'GMultipack'
            ])
            ->add('g_is_bundle', null, [
                'label' => 'GIs Bundle'
            ])
            ->add('g_energy_efficiency_class', null, [
                'label' => 'GEnergy Efficiency Class'
            ])
            ->add('g_min_energy_efficiency_class', null, [
                'label' => 'GMin Energy Efficiency Class'
            ])
            ->add('g_max_energy_efficiency_class', null, [
                'label' => 'GMax Energy Efficiency Class'
            ])
            ->add('g_age_group', ChoiceType::class, [
                'label' => 'GAge Group',
                'choices' => Google::getAgeGroupList()
            ])
            ->add('g_color', null, [
                'label' => 'GColor'
            ])
            ->add('g_gender', ChoiceType::class, [
                'label' => 'GGender',
                'choices' => Google::getGenderList()
            ])
            ->add('g_material', null, [
                'label' => 'GMaterial'
            ])
            ->add('g_pattern', null, [
                'label' => 'GPattern'
            ])
            ->add('g_size', null, [
                'label' => 'GSize'
            ])
            ->add('g_size_type', null, [
                'label' => 'GSize Type'
            ])
            ->add('g_size_system', null, [
                'label' => 'GSize System'
            ])
            ->add('g_item_group_id', null, [
                'label' => 'GItem Group Id'
            ])
            ->add('g_ads_redirect', null, [
                'label' => 'GAds Redirect'
            ])
            ->add('g_custom_label_0', null, [
                'label' => 'GCustom Label 0'
            ])
            ->add('g_custom_label_1', null, [
                'label' => 'GCustom Label 1'
            ])
            ->add('g_custom_label_2', null, [
                'label' => 'GCustom Label 2'
            ])
            ->add('g_custom_label_3', null, [
                'label' => 'GCustom Label 3'
            ])
            ->add('g_custom_label_4', null, [
                'label' => 'GCustom Label 4'
            ])
            ->add('g_promotion_id', null, [
                'label' => 'GPromotion Id'
            ])
            ->add('g_excluded_destination', null, [
                'label' => 'GExcluded Destination'
            ])
            ->add('g_included_destination', null, [
                'label' => 'GIncluded Destination'
            ])
            ->add('g_shipping', null, [
                'label' => 'GShipping'
            ])
            ->add('g_shipping_label', null, [
                'label' => 'GShipping Label'
            ])
            ->add('g_shipping_weight', null, [
                'label' => 'GShipping Weight'
            ])
            ->add('g_shipping_length', null, [
                'label' => 'GShipping Length'
            ])
            ->add('g_shipping_width', null, [
                'label' => 'GShipping Width'
            ])
            ->add('g_shipping_height', null, [
                'label' => 'GShipping Height'
            ])
            ->add('g_transit_time_label', null, [
                'label' => 'GTransit Time Label'
            ])
            ->add('g_max_handling_time', null, [
                'label' => 'GMax Handling Time'
            ])
            ->add('g_min_handling_time', null, [
                'label' => 'GMin Handling Time'
            ])
            ->add('g_tax', null, [
                'label' => 'GTax'
            ])
            ->add('g_tax_category', null, [
                'label' => 'GTax Category'
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    /**
     * {@inheritdoc}
     */   
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Google::class,
        ]);
    }
}
