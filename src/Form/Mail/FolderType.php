<?php

namespace App\Form\Mail;

use App\Entity\Mail\Folder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form type class of mail folder.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class FolderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Folder Name',
                'attr' => [
                    'readonly' => true,
                ]
            ])
            ->add('alias', null, [
                'label' => 'Folder Alias'
            ])
            ->add('fullpath', null, [
                'label' => 'Folder Fullpath',
                'attr' => [
                    'readonly' => true
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
            'data_class' => Folder::class,
        ]);
    }
}
