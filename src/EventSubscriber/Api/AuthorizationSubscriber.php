<?php
namespace App\EventSubscriber\Api;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

use App\Controller\ApiControllerAbstract;
use App\Entity\Api\User;

/**
 * Event subscriber class of api authorization.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class AuthorizationSubscriber implements EventSubscriberInterface
{
    /**
     * Api auth.
     * 
     * @param  ControllerEvent $event
     * @return mixed
     */
    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof ApiControllerAbstract) {

			$auth = $event->getRequest()->headers->get('Authorization');
			$user = $event->getRequest()->headers->get('User');

            if (!$auth || !$user) {
                throw new UnauthorizedHttpException('Authentication failed.');
            }

            $user = $controller[0]->getEntityManager()
        		->getRepository(User::class)
        		->findOneBy(['username' => $user]);

        	if (!$user || !$user->isActive()) {
        		throw new UnauthorizedHttpException('Authentication failed.');
        	}

        	if (!$user->compareAuthorizationKey($auth, true)) {
        		throw new UnauthorizedHttpException('Authentication failed.');
        	}
        }
    } 

    /**
     * {@inheritdoc}
     */ 
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}