<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;

use App\Controller\AdminControllerAbstract;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller of user
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class UserController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_user';

    /**
     * @Route("/admin/user/{page}", name="admin_user", requirements={"page"="\d+"})
     */
    public function index(Request $request, UserRepository $user, int $page = 1)
    {
        $template = 'admin/user/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/user/blocks/list.html.twig';
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
     * @Route("/admin/user/profile", name="admin_user_profile")
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();

        $password = $user->getPassword();

        $form = $this->createForm(UserType::class, $user, [
            'profile' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user->getPassword()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            }
            $user->setPassword($password)->setUpdatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);       
    }

    /**
     * @Route("/admin/user/add", name="admin_user_add")
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User;

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user
                ->setPassword($password)
                ->setIsActive(1)
                ->setCreatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/remove/{id}", name="admin_user_remove", requirements={"id"="\d+"})
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

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $user, int $id)
    {
        $event = $this->verifyData([
            'user' => $user->find($id)
        ]);        

        $user = $event->getObject('user');

        $password = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user->getPassword()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            }
            $user->setPassword($password)->setUpdatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');
        }

        return $this->render('admin/user/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
