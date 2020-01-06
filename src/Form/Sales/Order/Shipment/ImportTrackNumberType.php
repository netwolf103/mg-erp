<?php

namespace App\Form\Sales\Order\Shipment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

/**
 * 运单号导入表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ImportTrackNumberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */     
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'Track Number Csv File',
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/vnd.ms-excel',
                            'text/csv',
                            'text/plain',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid CSV File',
                    ])
                ],                
            ])
            ->add('save', SubmitType::class, ['label' => 'Import'])
        ;
    }

    /**
     * {@inheritdoc}
     */ 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
