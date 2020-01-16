<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form type class of web config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class WebType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_WEB_NAME, null, [
                'label' => 'Web Title',
                'data' => ConfigTrait::configWebname(),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_WEB_BRAND, null, [
                'label' => 'Brand Name',
                'data' => ConfigTrait::configBrand(),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_WEB_FREE_SHIPPING_MINIMUM_AMOUNT, null, [
                'label' => 'Free Shipping Minimum Amount',
                'data' => ConfigTrait::configFreeShippingMinimumAmount(),
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
