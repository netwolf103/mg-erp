<?php

namespace App\Controller\Admin\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Entity\Api\User;
use App\Repository\Api\UserRepository;
use App\Form\Api\UserType;

class UsersController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_api_users';

    /**
     * @Route("/admin/api/user/{page}", name="admin_api_users", requirements={"page"="\d+"})
     */
    public function index(Request $request, UserRepository $user, int $page = 1)
    {
        $template = 'admin/api/user/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/api/user/list.html.twig';
        }

        $results = $user->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }

    /**
     * @Route("/admin/api/user/add", name="admin_api_users_add")
     */
    public function add(Request $request)
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $user
                ->setIsActive(User::STATUS_ACTIVE)
                ->setCreatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addSuccessFlash('The user added!');

            return $this->redirectToRoute('admin_api_users');
        }

        return $this->render('admin/api/user/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/api/user/edit/{id}", name="admin_api_users_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, UserRepository $user, int $id)
    {
        $event = $this->verifyData([
            'user' => $user->find($id)
        ]);        

        $user = $event->getObject('user');

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addSuccessFlash('The user added!');
        }

        return $this->render('admin/api/user/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);        
    }

    /**
     * @Route("/admin/api/user/remove/{id}", name="admin_api_users_remove", requirements={"id"="\d+"})
     */
    public function remove(UserRepository $user, int $id)
    {
        $event = $this->verifyData([
            'user' => $user->find($id)
        ]);

        $user = $event->getObject('user');

        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        $this->addSuccessFlash('The user removed.');

        return $this->back();        
    }    
}
