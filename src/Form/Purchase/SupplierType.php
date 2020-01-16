<?php

namespace App\Form\Purchase;

use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form type class of Supplier.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SupplierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Supplier Name'])
            ->add('contact_name', TextType::class, ['label' => 'Contact Name'])
            ->add('contact_number', TextType::class, [
                'label'     => 'Contact Number',
                'required'  => false
            ])
            ->add('contact_email', TextType::class, [
                'label'     => 'Contact Email',
                'required'  => false
            ])
            ->add('contact_address', TextType::class, [
                'label'     => 'Contact Address',
                'required'  => false
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
            'data_class' => Supplier::class,
        ]);
    }
}
