<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Product\MediaType;
use App\Form\Product\OptionType;

use App\Entity\Product;
use App\Entity\Supplier;

/**
 * 产品表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Product Description',
                'attr' => [
                    'class' => 'form-control-sm'
                ]                   
            ])            
            ->add('sku', TextType::class, [
                'attr' => [
                    'readonly' => $entity->getId() ? true : false,
                    'class' => 'form-control-sm'
                ],
            ])
            ->add('url_path', TextType::class, [
                'label' => 'Url Key',
                'attr' => [
                    'readonly' => $entity->getId() ? true : false,
                    'class' => 'form-control-sm'
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Enabled' => 1,
                    'Disabled' => 0,
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]                   
            ])
            ->add('visibility', ChoiceType::class, [
                'choices' => [
                    'Not Visible Individually' => 1,
                    'Catalog' => 2,
                    'Search' => 3,
                    'Catalog, Search' => 4,
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]                   
            ])
            ->add('has_options', ChoiceType::class, [
                'label' => 'Has Options',
                'choices' => [
                    'No' => 0,
                    'Yes' => 1,
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]                   
            ])
            ->add('price', MoneyType::class, [
                'currency' => false,
                'attr' => [
                    'class' => 'form-control-sm',

                ]                 
            ])
            ->add('special_price', MoneyType::class, [
                'required' => false,
                'label' => 'Special Price',
                'currency' => false,
                'attr' => [
                    'class' => 'form-control-sm',

                ]                   
            ])
            ->add('purchase_price', MoneyType::class, [
                'required' => false,
                'label' => 'Purchase Price',
                'currency' => false,
                'attr' => [
                    'class' => 'form-control-sm',

                ]                   
            ])
            ->add('inventory', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]   
            ])
            ->add('options', CollectionType::class, [
                'entry_type' => OptionType::class,
                'entry_options' => [
                    'label' => false,
                ],             
                'label' => 'Custom Options',
                'label_attr' => [
                    'class' => 'bg-info text-white pl-2 pr-2'
                ],                
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-prototyp-level' => 0,
                    'class' => 'border border-top-0 border-info bg-light pt-3 pr-3 pb-3 pl-3'
                ]
            ])
            ->add('media', CollectionType::class, [
                'entry_type' => MediaType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'attr' => [
                    'class' => 'border border-top-0 border-info bg-light pt-3 pr-3 pb-3 pl-3',
                     'data-prototyp-type' => 'media'
                ],
                'label' => 'Images',
                'label_attr' => [
                    'class' => 'bg-info text-white pl-2 pr-2'
                ],                 
                'allow_add' => true,
                'allow_delete' => true,
            ])            
            ->add('images', FileType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'label' => 'Upload Images',
                // 'constraints' => [
                //     new File([
                //         'mimeTypes' => [
                //             'image/jpeg',
                //             'image/jpg',
                //             'image/png',
                //             'image/gif',
                //             'image/bmp',
                //         ]
                //     ])
                // ],                
            ])
            ->add('has_sample', null, [
                'label' => 'Has Sample?',                 
            ])            
            ->add('supplier', EntityType::class, [
                'class' => Supplier::class,
                'placeholder' => '-- Please Select --',
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                   
            ])
            ->add('purchase_url', null, [
                'label' => 'Purchase Url',
                'attr' => [
                    'class' => 'form-control-sm'
                ]                   
            ])
            ->add('save', SubmitType::class, ['label' => 'Save'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
