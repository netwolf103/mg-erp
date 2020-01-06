<?php

namespace App\Controller\Admin\Config\General;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Entity\Config\Core;
use App\Repository\Config\CoreRepository;
use App\Form\ConfigType;

/**
 * Controller for web config.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class WebController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/config/general/web", name="admin_config_general_web")
     */	
	public function config(Request $request, CoreRepository $coreRepository)
	{
        $form = $this->createForm(ConfigType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($form->getData() as $configs) {
            	$configs = Core::setConfigs($configs, $coreRepository);
	            foreach ($configs as $config) {
		            $entityManager->persist($config);
		            $entityManager->flush();
	            }
            }

            $this->addSuccessFlash('The configuration has been saved.');
        }        

        return $this->render('admin/config/core/form.html.twig', [
            'form' => $form->createView()
        ]);        
	}
}