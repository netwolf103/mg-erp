<?php

namespace App\Routing\Annotation;

use Symfony\Component\Routing\Annotation\Route as BaseRoute;

/**
 * Annotation class for @Route().
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class Route extends BaseRoute
{
	private $inMenu = false;

    public function setInMenu($inMenu)
    {
        $this->inMenu = $inMenu;
    }

    public function getInMenu()
    {
        return $this->inMenu;
    }	
}