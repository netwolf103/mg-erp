<?php

namespace App\Controller\Admin\Mail;

use App\Entity\Mail\Folder;
use App\Repository\Mail\FolderRepository;
use App\Form\Mail\FolderType;

use App\Controller\AdminControllerAbstract;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller of mail folder
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class FolderController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_mail_folder';

    /**
     * @Route("/admin/mail/folder", name="admin_mail_folder", requirements={"page"="\d+"})
     */
    public function index(Request $request, FolderRepository $folder, int $page = 1)
    {
        $template = 'admin/mail/folder/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/mail/folder/blocks/list.html.twig';
        }

        $results = $folder->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' 		=> $results,
            'filterSessionKey' 	=> $this->getFilterSessionKey(),
        ]);
    }

    /**
     * @Route("/admin/mail/folder/edit/{id}", name="admin_mail_folder_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, FolderRepository $folder, int $id)
    {
        $event = $this->verifyData([
            'folder' => $folder->find($id)
        ]);        

        $folder = $event->getObject('folder');

        $form = $this->createForm(FolderType::class, $folder);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $folder = $form->getData();

            $folder->setUpdatedAt(new \DateTimeImmutable());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($folder);
            $entityManager->flush();

            $this->addSuccessFlash('The changes saved!');
        }

        return $this->render('admin/mail/folder/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/mail/folder/pause/{id}", name="admin_mail_folder_pause", requirements={"id"="\d+"})
     */
    public function pause(FolderRepository $folder, int $id)
    {
        $event = $this->verifyData([
            'folder' => $folder->find($id)
        ]);

        $folder = $event->getObject('folder');

        $folder->setIsPause(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($folder);
        $entityManager->flush();
        $this->addSuccessFlash('The folder paused.');

        return $this->back(); 
    }

    /**
     * @Route("/admin/mail/folder/pause/cancel/{id}", name="admin_mail_folder_pause_cancel", requirements={"id"="\d+"})
     */
    public function pauseCancel(FolderRepository $folder, int $id)
    {
        $event = $this->verifyData([
            'folder' => $folder->find($id)
        ]);

        $folder = $event->getObject('folder');

        $folder->setIsPause(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($folder);
        $entityManager->flush();
        $this->addSuccessFlash('The folder pause canceled.');

        return $this->back(); 
    }    
}
