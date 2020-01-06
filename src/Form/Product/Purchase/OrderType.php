<?php

namespace App\Form\Product\Purchase;

use App\Entity\Product\Purchase\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use App\Form\Product\Purchase\Order\ItemType;

/**
 * 产品订单采购表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class OrderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shipping_amount', null, [
                'required' => true,
                'label' => 'Shipping Amount'
            ])
            ->add('source_order_number', null, [
                'required' => true,
                'label' => 'Order Numbers'
            ])
            ->add('track_number', null, [
                'label' => 'Track Number'
            ])
            ->add('comment', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Comment'
            ])            
            ->add('items', CollectionType::class, [
                'entry_type' => ItemType::class,
                'entry_options' => [
                    'label' => false,
                ],             
                'label' => 'Items',
                'label_attr' => [
                    'class' => 'bg-info text-white pl-2 pr-2'
                ],                
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-prototyp-level' => 1,
                    'class' => 'border border-top-0 border-info bg-light pt-3 pr-3 pb-3 pl-3'
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
            'data_class' => Order::class,
        ]);
    }
}
