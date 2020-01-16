<?php

namespace App\Form\Product;

use App\Entity\Product\Option;
use App\Entity\Product\Option\Dropdown;
use App\Entity\Product\Option\Field;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use App\Form\Product\Option\FieldType;
use App\Form\Product\Option\DropdownType;

/**
 * Form type class of product Option.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Input Type',
                'placeholder' => '-- Please Select --',
                'choices' => [
                    'Drop-down' => Dropdown::OPTION_TYPE,
                    'Field' => Field::OPTION_TYPE,
                ],
                'attr' => [
                    'class' => 'form-control-sm',
                    'data-option-type' => 'select'
                ]                
            ])
            ->add('sort_order', IntegerType::class, [
                'label' => 'Sort Order',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])
            ->add('is_require', ChoiceType::class, [
                'label' => 'Is Required',
                'required' => false,
                'placeholder' => false,
                'choices' => [
                    'Yes' => 1,
                    'No' => 0,
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]                
            ])          
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $optionType = $event->getData();
            $form = $event->getForm();

            if ($optionType instanceof Option) {
                switch ($optionType->getType()) {
                    case Dropdown::OPTION_TYPE:
                        $form->add('optionValuesDropdown', CollectionType::class, [
                            'label' => false,
                            'entry_type' => DropdownType::class,
                            'entry_options' => [
                                'label' => false,
                                'option_values' => $optionType->getOptionValuesDropdown()
                            ],
                            'allow_add' => true,
                            'allow_delete' => true,                
                        ]);
                        break;

                    case Field::OPTION_TYPE:
                        $form->add('optionValueField', FieldType::class, [
                            'label' => false,
                        ]);
                        break;
                }
            }
            
        });        
    }

    /**
     * {@inheritdoc}
     */ 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Option::class,
        ]);
    }
}
