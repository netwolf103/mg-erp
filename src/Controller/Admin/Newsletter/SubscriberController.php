<?php

namespace App\Controller\Admin\Newsletter;

use App\Entity\Newsletter\Subscriber;
use App\Repository\Newsletter\SubscriberRepository;

use App\Controller\AdminControllerAbstract;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/newsletter/subscriber/{page}", name="admin_newsletter_subscriber", requirements={"page"="\d+"})
     */
    public function index(Request $request, SubscriberRepository $subscriber, int $page = 1)
    {
        $template = 'admin/newsletter/subscriber/index.html.twig';

        if ($request->isXmlHttpRequest()) {
            $this->handleFilter($request);
            $template = 'admin/newsletter/subscriber/blocks/list.html.twig';
        }

        $results = $subscriber->getAll(
            $this->getSessionFilterQuery($request),
            $page,
            $this->getSessionLimit($request)
        );

        return $this->render($template, [
            'paginator' => $results,
            'filterSessionKey' => $this->getFilterSessionKey(),
        ]);
    }
}
