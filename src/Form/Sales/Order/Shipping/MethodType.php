<?php

namespace App\Form\Sales\Order\Shipping;

use App\Entity\SaleOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\Config\Shipping\MethodRepository;

/**
 * 订单Shipping Method表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MethodType extends AbstractType
{
    private $methodRepository;

    public function __construct(MethodRepository $methodRepository)
    {
        $this->methodRepository = $methodRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $method = $this->methodRepository;
        $shipping_method = $entity->getShippingMethod();
        $shipping_method = explode('_', $shipping_method);
        $shipping_method_code = reset($shipping_method);
        $shipping_method_id = $this->methodRepository->findOneBy(['code' => $shipping_method_code])->getId();

        $builder
            ->add('shipping_method', ChoiceType::class, [
                'label' => 'Shipping Method',
                'data' => $shipping_method_id,
                'choice_loader' => new CallbackChoiceLoader(function() use ($method){
                    $configMethods = $method->findAll();

                    $choice = [];
                    foreach ($configMethods as $configMethod) {
                        $choice[$configMethod->getTitle() .' - '. $configMethod->getName() .' - '.'$'. number_format($configMethod->getPrice(), 2)] = $configMethod->getId();
                    }

                    return $choice;
                })
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
            'data_class' => SaleOrder::class,
        ]);
    }
}
