<?php

namespace App\EventListener\ORM;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Listener of controller.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class EntityListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}