<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

use App\Controller\AdminControllerAbstract;

/**
 * Abstract controller for Api
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
abstract class ApiControllerAbstract extends AdminControllerAbstract
{
    /**
     * Gets a object manager.
     *
     * @return ObjectManager
     */	
	public function getEntityManager()
	{
		return $this->getDoctrine()->getManager();
	}

    /**
     * Returns a JsonResponse.
     */
	final public function jsonSuccess($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
	{
		$data = array_merge(['success' => true], $data);

		return $this->json($data, $status, $headers, $context);
	}

    /**
     * Returns a JsonResponse.
     */
	final public function jsonError($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
	{
		$data = array_merge(['success' => false], $data);

		return $this->json($data, $status, $headers, $context);		
	}
}