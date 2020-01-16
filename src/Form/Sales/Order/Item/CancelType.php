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
 * Form type class of Cancel.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CancelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $builder
            ->add('qty_ordered', null, [
                'required' => false,
                'disabled' => true,
                'label' => 'Qty Ordered'
            ])
            ->add('qty_canceled', IntegerType::class, [
                'label' => 'Qty Canceled',
                'data' => 1,
                'attr' => [
                    'min' => 1,
                    'max' => $entity->getQtyOrdered() - $entity->getQtyCanceled() - $entity->getQtyRefunded()
                ]
            ])
            ->add('refund_amount', null, [
                'mapped' => false,
                'required' => true,
                'label' => 'Refund Amount',
                'data' => round(($entity->getRowtotal() - $entity->getDiscountAmount()) / $entity->getQtyOrdered(), 2)
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
