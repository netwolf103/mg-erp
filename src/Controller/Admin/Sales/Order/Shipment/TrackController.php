<?php

namespace App\Controller\Admin\Sales\Order\Shipment;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Controller\AdminControllerAbstract;

use App\Repository\Sales\Order\Shipment\TrackRepository;

use App\Form\Sales\Order\Shipment\TrackType;

/**
 * Controller of shipment track.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class TrackController extends AdminControllerAbstract
{
    /**
     * @Route("/admin/sales/order/shipment/track/edit/{id}", name="admin_sales_shipment_track_edit", requirements={"id"="\d+"})
     */	
	public function edit(Request $request, TrackRepository $track, $id)
	{
        $event = $this->verifyData([
            'track' => $track->find($id)
        ]);

        $track = $event->getObject('track');

        $form = $this->createForm(TrackType::class, $track);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $track = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($track);
            $entityManager->flush();

            $this->addSuccessFlash('The track updated.');                  
        }

        return $this->render('admin/sales/order/shipment/track/form.html.twig', [
            'form' => $form->createView(),
            'track' => $track,
            'order' => false
        ]);                  
	}
}