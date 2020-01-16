<?php

namespace App\EventListener\ORM;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Event listener class of entity.
 *
 * @author Zhang Zhao <netwolf103@gmail.com>
 */
class EntityListener
{
	/**
	 * Entity pre update.
	 * 
	 * @param  LifecycleEventArgs $args
	 * @return mixed
	 */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}