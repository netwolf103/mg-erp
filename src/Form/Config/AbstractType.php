<?php

namespace App\Form\Config;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType as FormAbstractType;
use App\Repository\Config\CoreRepository;
use App\Traits\ConfigTrait;

/**
 * Config type abstract class.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class AbstractType extends FormAbstractType
{
    public function __construct(EntityManagerInterface $em)
    {
        ConfigTrait::loadConfigs($em);
    }  
}