<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Entity\AuditableEntityInterface;
use FOS\UserBundle\Model\User;


class EntityUpdateListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        // Note: it is not possible to directly inject the security.context here since that would
        // introduce a circular dependency
        $this->container = $container;
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity = $event->getEntity();

        if ($entity instanceof AuditableEntityInterface)
        {
            $uid = ($user instanceof User ? $user->getId() : -1);

            $entity->setLastUpdatedDate(new \DateTime());
            $entity->setLastUpdatedBy($uid);
        }
    }
}
