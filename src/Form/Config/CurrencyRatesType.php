<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form for currency rates config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CurrencyRatesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_RATES_USD, null, [
                'label' => 'USD',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_USD),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_AUD, null, [
                'label' => 'AUD',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_AUD),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_CAD, null, [
                'label' => 'CAD',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_CAD),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_EUR, null, [
                'label' => 'EUR',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_EUR),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_GBP, null, [
                'label' => 'GBP',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_GBP),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_MXN, null, [
                'label' => 'MXN',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_MXN),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_NZD, null, [
                'label' => 'NZD',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_NZD),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_PHP, null, [
                'label' => 'PHP',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_PHP),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_RATES_SGD, null, [
                'label' => 'SGD',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_RATES_SGD),
                'attr' => ['class' => 'form-control-sm']
            ])                                                                                         
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
