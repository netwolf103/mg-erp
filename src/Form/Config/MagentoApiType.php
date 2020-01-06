<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form for magento api config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MagentoApiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_MAGENTO_API_ENABLED, ChoiceType::class, [
                'label' => 'Enable',
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_ENABLED),
                'attr' => ['class' => 'form-control-sm']
            ]) 
            ->add(Core::CONFIG_PATH_MAGENTO_API_USER, null, [
                'label' => 'User',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_USER),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_MAGENTO_API_KEY, null, [
                'label' => 'Key',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_KEY),
                'attr' => ['class' => 'form-control-sm']
            ]) 
            ->add(Core::CONFIG_PATH_MAGENTO_API_URL, null, [
                'label' => 'Url',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_MAGENTO_API_URL),
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
