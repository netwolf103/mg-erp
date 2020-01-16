<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form type class of PaypalApi config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class PaypalApiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_PAYPAL_API_ENABLED, ChoiceType::class, [
                'label' => 'Enable',
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_PAYPAL_API_ENABLED),
                'attr' => ['class' => 'form-control-sm']
            ])        
            ->add(Core::CONFIG_PATH_PAYPAL_API_SANDBOX, ChoiceType::class, [
                'label' => 'Sandbox Model',
                'required' => false,
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_PAYPAL_API_SANDBOX),
                'attr' => ['class' => 'form-control-sm']
            ])        
            ->add(Core::CONFIG_PATH_PAYPAL_API_VERSION, null, [
                'label' => 'Api Version',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_PAYPAL_API_VERSION),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_PAYPAL_API_CLIENT_ID, null, [
                'label' => 'Client Id',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_PAYPAL_API_CLIENT_ID),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_PAYPAL_API_CLIENT_SECRET, null, [
                'label' => 'Client Secret',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_PAYPAL_API_CLIENT_SECRET),
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
