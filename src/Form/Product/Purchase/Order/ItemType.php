<?php

namespace App\Form\Product\Purchase\Order;

use App\Entity\Product\Purchase\Order\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 产品订单采购表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('size', null, [
                'label' => 'Ring Size'
            ])
            ->add('price', null, [
                'required' => true,
                'label' => 'Unit Price'
            ])
            ->add('qty_ordered', null, [
                'required' => true,
                'label' => 'Purchase Quantity'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
