<?php

namespace App\Form\Api;

use App\Entity\Api\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Form for api user.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('securityKey')
            ->add('is_active', ChoiceType::class, [
                    'choices' => User::getStatusList()
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
            'data_class' => User::class,
        ]);
    }
}
