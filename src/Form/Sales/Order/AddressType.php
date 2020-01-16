<?php

namespace App\Form\Sales\Order;

use App\Entity\Sales\Order\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

/**
 * Form type class of Address
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Firstname'
            ])
            ->add('lastname', null, [
                'label' => 'Lastname'
            ])
            ->add('street', null, [
                'label' => 'Street'
            ])
            ->add('city', null, [
                'label' => 'City'
            ])
            ->add('region', null, [
                'label' => 'Region'
            ])
            ->add('postcode', null, [
                'label' => 'Postcode'
            ])
            ->add('country_id', CountryType::class, [
                'label' => 'Country Id',
                'placeholder' => '-- Please Select --'
            ])
            ->add('telephone')
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    /**
     * {@inheritdoc}
     */ 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
