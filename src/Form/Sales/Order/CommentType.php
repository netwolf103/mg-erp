<?php

namespace App\Form\Sales\Order;

use App\Entity\SaleOrder;
use App\Entity\Sales\Order\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form type class of Comment
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();

        $choices = array_flip(SaleOrder::getStatusList());

        $filterStatus = [
            SaleOrder::ORDER_STATUS_COMPLETE,
            SaleOrder::ORDER_STATUS_UNDELIVERED,
            SaleOrder::ORDER_STATUS_RESHIPPING
        ];

        if (in_array($entity->getStatus(), $filterStatus)) {
            $choices = array_filter($choices, function($status) use ($filterStatus) {
                return in_array($status, $filterStatus);
            });
        }

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Order Status',
                'choices' => $choices
            ])
            ->add('comment', null, [
                'label' => 'Order Comment',
                'required' => true
            ])
            ->add('is_customer_notified', CheckboxType::class, [
                'label' => 'Notify Customer by Email',
                'required' => false
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
            'data_class' => Comment::class,
        ]);
    }
}
