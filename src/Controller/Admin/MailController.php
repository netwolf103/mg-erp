<?php

namespace App\Controller\Admin;

use App\Entity\Mail;
use App\Repository\MailRepository;
use App\Entity\Mail\Folder;
use App\Repository\Mail\FolderRepository;

use App\Controller\AdminControllerAbstract;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller of mail
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class MailController extends AdminControllerAbstract
{
    /**
     * {@inheritdoc}
     */     
    protected static $_defaultRoute = 'admin_mail';

    /**
     * @Route("/admin/mail/{page}", name="admin_mail", requirements={"page"="\d+"})
     */
    public function index(Request $request, MailRepository $mail, FolderRepository $folder, int $page = 1)
    {
        $template = 'admin/mail/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);

            $template = 'admin/mail/blocks/list.html.twig';
        }

        $results = $mail->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' 		=> $results,
            'folder' 			=> $folder->getAll([], 1, 100),
            'filterSessionKey' 	=> $this->getFilterSessionKey(),
        ]);
    }

    /**
     * @Route("/admin/mail/view/{id}", name="admin_mail_view", requirements={"id"="\d+"})
     */
    public function view(Request $request, MailRepository $mail, int $id)
    {
        $event = $this->verifyData([
            'mail' => $mail->find($id)
        ]);        

        $mail = $event->getObject('mail');

        return $this->render('admin/mail/view.html.twig', [
            'mail' => $mail
        ]);
    }  

    /**
     * @Route("/admin/mail/reply/{id}", name="admin_mail_reply", requirements={"id"="\d+"})
     */
    public function reply(Request $request, MailRepository $mail, FolderRepository $folder, int $id)
    {
        $event = $this->verifyData([
            'mail' => $mail->find($id)
        ]);        

        $mail = $event->getObject('mail');

        return $this->render('admin/mail/reply.html.twig', [
            'mail' => $mail
        ]);
    }    
}
