<?php

namespace App\Form\Product;

use App\Entity\Product\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Form type class of product Media.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('label', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('position', null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('exclude', ChoiceType::class, [
                'required' => false,
                'placeholder' => false,
                'choices' => [
                    'No' => 0,
                    'Yes' => 1,
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])          
            ->add('url', HiddenType::class, [
                'required' => false
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
