<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Traits\ConfigTrait;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Twig extension class of config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ConfigExtension extends AbstractExtension
{
    /**
     * Init entity manager.
     *
     * @param EntityManagerInterface $em [description]
     */
    public function __construct(EntityManagerInterface $em)
    {
        ConfigTrait::loadConfigs($em);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('site_name', [$this, 'getSiteName']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getSiteName()
    {
        return ConfigTrait::configWebname();
    } 
}
