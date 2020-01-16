<?php

namespace App\Form\Sales\Order\Shipment;

use App\Entity\Sales\Order\Shipment\Track;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Form type class of shipment Track
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TrackType extends AbstractType
{
    private $translator;

    /**
     * Init Translator
     * 
     * @param TranslatorInterface $translator
     */

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */       
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $orderEntity = $options['order_entity'] ?? false;

        $builder
            ->add('carrier_code', HiddenType::class, [
                'data' => 'custom'
            ])
            ->add('title', null, [
                'label' => 'Carrier',
                'required' => true
            ])
            ->add('track_number', null, [
                'label' => 'Track Number',
                'required' => true
            ])
        ;

        if ($orderEntity) {
            foreach ($orderEntity->getItems() as $item) {
                if (!$item->canShip()) {
                    continue;
                }

                if ($item->getIsVirtual()) {
                   continue;
                }

                $builder->add('item:id:'. $item->getId(), IntegerType::class, [
                    'mapped' => false,
                    'required' => true,
                    'label' => $this->translator->trans(
                        'sku (Qty Ordered: qty_ordered, Qty Shipped: qty_shipped, Qty Canceled: qty_canceled, Qty Refunded: qty_refunded)',
                        [
                            'sku' => $item->getSku(), 
                            'qty_ordered' => $item->getQtyOrdered(), 
                            'qty_shipped' => $item->getQtyShipped(), 
                            'qty_canceled' => $item->getQtyCanceled(), 
                            'qty_refunded' => $item->getQtyRefunded()
                        ]
                    ),
                    'data' => $item->getCanQtyShipped(),
                    'attr' => [
                        'min' => 0,
                        'max' => $item->getCanQtyShipped(),
                    ]
                ]);
            }
        }

        $builder->add('save', SubmitType::class, ['label' => 'Save']);
    }

    /**
     * {@inheritdoc}
     */   
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
            'order_entity' => false,
        ]);
    }
}
