<?php

namespace App\Form\Sales\Order;

use App\Entity\Sales\Order\Item;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Intl\Intl;
use App\Currency\Rates;

/**
 * 订单Item表单
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ItemType extends AbstractType
{
    private $router;
    private $product;
    private $rates;

    public function __construct(RouterInterface $router, ParameterBagInterface $parameter, ProductRepository $product)
    {
        $this->router = $router;
        $this->rates = new Rates($parameter);
        $this->product = $product;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity             = $builder->getData();
        $order              = $options['order'];
        $is_exchange        = $options['is_exchange'];

        $option_size        = $entity->getProductOptionSize();
        $option_engravings  = $entity->getProductOptionEngravings();

        $product = $this->product->findOneBy(['sku' => $entity->getSku()]);
        if (!$product) {
            $product = new Product;
        }

        $builder
            ->add('sku', null, [
                'attr' => [
                    'readonly' => $entity->getId() ? true : false,
                    'role' => 'select-item-sku',
                    'action-xhr' => $this->router->generate('admin_product_size'),
                    'data-currency-code' => $order->getOrderCurrencyCode()
                ],
            ])
            ->add('qty_ordered', null, [
                'label' => 'Qty Ordered',
            ])
            ->add('cart_discount_percent', PercentType::class, [
                'required' => false,
                'type' => 'integer',
                'mapped' => false,
                'label' => 'Add To Cart Discount Percent',
                'data' => 0
            ])
            ->add('discount_percent', PercentType::class, [
                'required' => false,
                'type' => 'integer',
                'label' => 'Discount Percent',
                'empty_data' => 0
            ])                          
        ;

        $symbol = $this->currencyCode($entity);

        if ($product->getOptionSizes() || !$entity->getId()) {
            $builder->add('option_size', ChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'validation_groups' => false,
                'label' => 'Option Sizes',
                'placeholder' => '-- Please Select --',
                'attr' => [
                    'role' => 'select-item-size-list'
                ],
                'choice_loader' => new CallbackChoiceLoader(function () use ($product, $symbol) {
                    $optionList = [];
                    foreach ($product->getOptionSizes() as $value) {
                        $title = $value->getTitle();
                        if ($value->getPrice()) {
                            $title .= sprintf('+%s', $this->convertPrice($value->getPrice(), $symbol, true));
                        }

                        $optionList[$title] = $value->getId();
                    }
                    return $optionList;
                }),
                'data' => $option_size['option_value'] ?? null,
            ]);
        }

        if ($product->getOptionEngravings() || !$entity->getId()) {
            $builder->add('option_engravings', null, [
                'mapped' => false,
                'label' => 'Option Engravings',
                'data' => $option_engravings['value'] ?? null,
                'help' => sprintf('+%s', $this->convertPrice(15, $symbol, true)),
                'help_attr' => [
                    'class' => 'text-danger'
                ],
            ]);
        }

        if ($is_exchange) {
            $builder->add('refund_amount', null, [
                'mapped' => false,
                'label' => 'Refund Amount',
            ])
            ->add('carrier_name', null, [
                'mapped' => false,
                'label' => 'Carrier',
            ])              
            ->add('track_number', null, [
                'mapped' => false,
                'label' => 'Track Number',
            ]);             
        }

        $builder->add('save', SubmitType::class, ['label' => 'Save']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
            'order' => null,
            'is_exchange' => false,
        ]);
    }

    /**
     * Convert price.
     * 
     * @param  float        $price
     * @param  string       $symbol
     * @param  bool|boolean $format
     * @return float:string
     */
    private function convertPrice(float $price, string $symbol, bool $format = false)
    {
        $price = $this->rates->convert($price, $symbol);

        return $format ? Intl::getCurrencyBundle()->getCurrencySymbol($symbol) . $price : $price;
    }

    /**
     * Return currency code from order.
     * 
     * @param  Item   $item
     * @param  string $defaultCode
     * @return string
     */
    private function currencyCode(Item $item, string $defaultCode = 'USD'): string
    {
        return $item->getParent() ? $item->getParent()->getOrderCurrencyCode() : $defaultCode;
    }
}
