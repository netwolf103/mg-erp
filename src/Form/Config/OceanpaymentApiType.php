<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form type class of Oceanpayment config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OceanpaymentApiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_OCEANPAYMENT_API_ENABLED, ChoiceType::class, [
                'label' => 'Enable',
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_OCEANPAYMENT_API_ENABLED),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_OCEANPAYMENT_API_ACCOUNT, null, [
                'label' => 'Account',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_OCEANPAYMENT_API_ACCOUNT),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_OCEANPAYMENT_API_TERMINAL, null, [
                'label' => 'Terminal',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_OCEANPAYMENT_API_TERMINAL),
                'attr' => ['class' => 'form-control-sm']
            ]) 
            ->add(Core::CONFIG_PATH_OCEANPAYMENT_API_SECURE_CODE, null, [
                'label' => 'SecureCode',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_OCEANPAYMENT_API_SECURE_CODE),
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
