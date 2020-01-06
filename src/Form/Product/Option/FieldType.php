<?php

namespace App\Form\Product\Option;

use App\Entity\Product\Option\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * 产品自定义选项值表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class FieldType extends AbstractType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', null, [
                'attr' => [
                    'class' => 'form-control-sm'
                ]  
            ])
            ->add('price_type', ChoiceType::class, [
                'label' => 'Price Type',
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
                'attr' => [
                    'class' => 'form-control-sm'
                ]  
            ])
            ->add('max_characters', null, [
                'label' => 'Max Characters',
                'attr' => [
                    'class' => 'form-control-sm'
                ]  
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Field::class,
        ]);
    }
}
