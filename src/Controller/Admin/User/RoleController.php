<?php

namespace App\Controller\Admin\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Entity\User\Role;
use App\Repository\User\RoleRepository;
use App\Form\User\RoleType;

/**
 * Controller for User Role
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class RoleController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/role/{page}", name="admin_role", requirements={"page"="\d+"})
     */
    public function index(Request $request, RoleRepository $role, int $page = 1)
    {
        $template = 'admin/user/role/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/user/role/blocks/list.html.twig';
        }

        $results = $role->getAll(
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
     * @Route("/admin/role/add", name="admin_role_add")
     */
    public function add(Request $request)
    {
        $role = new Role;

        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            $this->addSuccessFlash('The user role added!');

            return $this->redirectToRoute('admin_role');                        
        }

        return $this->render('admin/user/role/form.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/role/edit/{id}", name="admin_role_edit", requirements={"id"="\d+"})
     */    
    public function edit(Request $request, RoleRepository $role, int $id)
    {
        $event = $this->verifyData([
            'role' => $role->find($id)
        ]);        

        $role = $event->getObject('role');

        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            $this->addSuccessFlash('The user role updated!');                   
        }

        return $this->render('admin/user/role/form.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);        
    }
}
