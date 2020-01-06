<?php

namespace App\Form\Product\Purchase\Order;

use App\Entity\Product\Purchase\Order;
use App\Entity\Product\Purchase\Order\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * 产品采购采购单备注表单
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
        $choices = array_flip(Order::getStatusList());

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Order Status',
                'choices' => $choices
            ])        
            ->add('comment')
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
