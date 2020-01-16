<?php

namespace App\Form\Config;

use App\Form\Config\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Config\Core;
use App\Traits\ConfigTrait;

/**
 * Form type class of GoogleMerchants.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class GoogleMerchantsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_ENABLED, ChoiceType::class, [
                'label' => 'Enable',
                'choices' => [
                    'No' => 0, 
                    'Yes' => 1
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_ENABLED),
                'attr' => ['class' => 'form-control-sm']
            ])         
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_ID, null, [
                'label' => 'Merchants Id',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_ID),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_TYPE, ChoiceType::class, [
                'label' => 'Api Type',
                'required' => false,
                'choices' => [
                    'Service Account' => 'service_account'
                ],
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_TYPE),                
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PROJECT_ID, null, [
                'label' => 'Api Project Id',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PROJECT_ID),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY_ID, null, [
                'label' => 'Api Private Key Id',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY_ID),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY, TextareaType::class, [
                'label' => 'Api Private Key',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_PRIVATE_KEY),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_EMAIL, null, [
                'label' => 'Api Client Email',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_EMAIL),
                'attr' => ['class' => 'form-control-sm']
            ]) 
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_ID, null, [
                'label' => 'Api Client Id',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_ID),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_URI, null, [
                'label' => 'Api Auth Uri',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_URI),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_TOKEN_URI, null, [
                'label' => 'Api Token Uri',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_TOKEN_URI),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_CERT_URL, null, [
                'label' => 'Api Auth Provider x509 Cert Url',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_AUTH_CERT_URL),
                'attr' => ['class' => 'form-control-sm']
            ])
            ->add(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_CERT_URL, null, [
                'label' => 'Api Client x509 Cert Url',
                'required' => false,
                'data' => ConfigTrait::getConfigValue(Core::CONFIG_PATH_GOOGLE_MERCHANTS_API_CLIENT_CERT_URL),
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
