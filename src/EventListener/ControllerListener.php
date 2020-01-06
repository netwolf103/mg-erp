<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;

/**
 * Listener of controller.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class ControllerListener
{
    use ControllerTrait;

    /**
     * @param App\Event\Sales\Order\ControllerEvent|Event $event
     * @return void
     */
    public function onControllerDataVerify(Event $event): void
    {
        foreach ($event->getObjects() as $entity => $objectRepository) {
            if ($objectRepository instanceof Doctrine\Common\Persistence\Proxy) {
                continue;
            }

            if (!$objectRepository) {
                throw $this->createNotFoundException(sprintf('No %s found', $entity));
            }
        }
    }
}