<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form type class of YunExpress Api config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class YunExpress extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_YUNEXPRESS_API_ENABLED, ChoiceType::class, [
                'label' => 'Enable',
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_YUNEXPRESS_API_ENABLED),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_YUNEXPRESS_API_SANDBOX, ChoiceType::class, [
                'label' => 'Development Model?',
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_YUNEXPRESS_API_SANDBOX),
                'attr' => ['class' => 'form-control-sm']
            ])             
            ->add(Core::CONFIG_PATH_YUNEXPRESS_API_ACCOUNT, null, [
                'label' => 'API Id',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_YUNEXPRESS_API_ACCOUNT),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_YUNEXPRESS_API_SECRET, null, [
                'label' => 'API Secret',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_YUNEXPRESS_API_SECRET),
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
