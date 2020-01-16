<?php

namespace App\Controller\Admin\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\UserRepository;

/**
 * Controller of user login history
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class LoginHistoryController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/supper/user/{id}/login/history", name="admin_user_login_history", requirements={"id"="\d+"})
     */    
    public function index(Request $request, UserRepository $user, int $id)
    {
        $event = $this->verifyData([
            'user' => $user->find($id)
        ]);        

        $user = $event->getObject('user');

        return $this->render('admin/user/login-history/index.html.twig', [
            'user' => $user,
        ]);
    }
}