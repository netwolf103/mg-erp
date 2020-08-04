<?php

namespace App\Twig\Newsletter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Twig extension class of newsletter subscriber.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class SubscriberExtension extends AbstractExtension
{
    private $translator;
    private $parameter;

    /**
     * Init TranslatorInterface
     * 
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, ParameterBagInterface $parameter)
    {
        $this->translator = $translator;
        $this->parameter = $parameter;       
    }

    /**
     * {@inheritdoc}
     */ 
    public function getFilters(): array
    {
        return [
            new TwigFilter('subscriber_type', [$this, 'getSubscriberType']),
            new TwigFilter('subscriber_status', [$this, 'getSubscriberStatus'])
        ];
    }

    /**
     * Return subject type
     * 
     * @param  int   $value
     * @return string
     */
    public function getSubscriberType(int $value): string
    {
        $types = $this->getSubscriberTypeList();

        return $types[$value] ?? $value;
    }

    /**
     * Return subject type
     * 
     * @param  int   $value
     * @return string
     */
    public function getSubscriberStatus(int $value): string
    {
        $status = $this->getSubscriberStatusList();

        return $status[$value] ?? $value;
    }    

    /**
     * {@inheritdoc}
     */ 
    public function getFunctions(): array
    {
        return [
            new TwigFunction('subscriber_type_list', [$this, 'getSubscriberTypeList']),
            new TwigFunction('subscriber_status_list', [$this, 'getSubscriberStatusList']),
        ];
    }

    /**
     * Return subscriber type list.
     * 
     * @return array
     */
    public function getSubscriberTypeList(): array
    {
        return [1 => $this->translator->trans('Guest'), 2 => $this->translator->trans('Customer')];
    }

    /**
     * Return subscriber status list.
     * 
     * @return array
     */
    public function getSubscriberStatusList(): array
    {
        return [1 => $this->translator->trans('Subscribed'), 2 => $this->translator->trans('Not Activated'), 3 => $this->translator->trans('Unsubscribed'), 4 => $this->translator->trans('Unconfirmed')];
    }
}
