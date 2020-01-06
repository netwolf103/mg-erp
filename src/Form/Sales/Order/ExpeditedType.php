<?php

namespace App\Form\Sales\Order;

use App\Entity\Sales\Order\Expedited;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form for Order Expedited
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ExpeditedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', null, [
                'required' => true,
                'label' => 'Expedited Comment'
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
            'data_class' => Expedited::class,
        ]);
    }
}
