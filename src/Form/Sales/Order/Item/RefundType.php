<?php

namespace App\Form\Sales\Order\Item;

use App\Entity\Sales\Order\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * 订单Item refund表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RefundType extends AbstractType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $maxQtyRefunded = $entity->getQtyOrdered() - $entity->getQtyCanceled() - $entity->getQtyRefunded();
        if ($maxQtyRefunded <= 0) {
            $maxQtyRefunded = 1;
        }

        $builder
            ->add('qty_ordered', null, [
                'required' => false,
                'disabled' => true,
                'label' => 'Qty Ordered'
            ])
            ->add('qty_refunded', IntegerType::class, [
                'label' => 'Qty Refunded',
                'data' => 1,
                'attr' => [
                    'min' => 1,
                    'max' => $maxQtyRefunded
                ]
            ])
            ->add('refund_amount', null, [
                'mapped' => false,
                'required' => true,
                'label' => 'Refund Amount',
                'data' => round(($entity->getRowtotal() - $entity->getDiscountAmount()) / $entity->getQtyOrdered(), 2)
            ])              
            ->add('carrier_name', null, [
                'mapped' => false,
                'required' => true,
                'label' => 'Carrier',
            ])              
            ->add('track_number', null, [
                'mapped' => false,
                'required' => true,
                'label' => 'Track Number',
            ])              
            ->add('comment', TextareaType::class, [
                'mapped' => false
            ])            

            ->add('save', SubmitType::class, ['label' => 'Save']);
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
