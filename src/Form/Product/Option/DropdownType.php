<?php

namespace App\Form\Product\Option;

use App\Entity\Product\Option\Dropdown;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Form type class of product option Dropdown.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class DropdownType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $option_values = $options['option_values'] ?? [];

        $builder
            ->add('title', null, [
                'label' =>  'Title',
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('price',  null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('price_type', ChoiceType::class, [
                'label' => 'Price Type',
                'required' => false,
                'placeholder' => false,
                'choices' => [
                    'Fixed' => 'fixed',
                    'Percent' => 'percent',
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('sku', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('inventory', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('inventory_low', null, [
                'label' => 'Inventory Low Alert',
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])            
            ->add('sort_order', IntegerType::class, [
                'label' => 'Sort Order',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('parent_option', ChoiceType::class, [
                'required' => false,
                'placeholder' => '-- Please Select --',
                'label' => 'Parent Option',
                'attr' => [
                    'class' => 'form-control-sm'
                ],              
                'choices' => $option_values,
                'choice_label' => function(Dropdown $optionDropdown) {
                    return $optionDropdown->getTitle();
                },                            
            ])
        ;             
    }

    /**
     * {@inheritdoc}
     */ 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dropdown::class,
            'option_values' => []
        ]);
    }
}
