<?php

namespace App\Controller\Admin;

use App\Controller\AdminControllerAbstract;

use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for Stat
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class StatController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/stat", name="admin_stat")
     */
    public function index()
    {
        return $this->render('admin/stat/index.html.twig', [
            'controller_name' => 'StatController',
        ]);
    }
}
