<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\Config\WebType;
use App\Form\Config\CurrencyRatesType;
use App\Form\Config\GoogleMerchantsType;
use App\Form\Config\MagentoApiType;
use App\Form\Config\YunExpress;

/**
 * Form type class of Config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('web', WebType::class, [
                'label' => 'General',
                'attr' => ['class' => 'fieldset-block']
            ])
            ->add('rates', CurrencyRatesType::class, [
                'label' => 'Currency Rates',
                'attr' => ['class' => 'fieldset-block']
            ])
            ->add('magentoApi', MagentoApiType::class, [
                'label' => 'Magento Soap',
                'attr' => ['class' => 'fieldset-block']
            ])            
            ->add('googleMerchants', GoogleMerchantsType::class, [
                'label' => 'Google Merchants',
                'attr' => ['class' => 'fieldset-block']
            ])
            ->add('yunExpress', YunExpress::class, [
                'label' => 'YunExpress API',
                'attr' => ['class' => 'fieldset-block']
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
            // Configure your form options here
        ]);
    }
}
