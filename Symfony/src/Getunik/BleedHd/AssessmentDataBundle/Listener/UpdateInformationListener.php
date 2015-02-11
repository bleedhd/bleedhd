<?php

/*
 * Copyright(c) 2015, getunik AG (http://www.getunik.com)
 * ALL Rights Reserved
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of getunik AG and its suppliers, if any.
 * The intellectual and technical concepts contained
 * herein are proprietary to getunik AG and its suppliers and
 * may be covered by Swiss and Foreign Patents, patents in
 * process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from getunik AG.
 */

namespace Getunik\BleedHd\AssessmentDataBundle\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Entity\UpdateInformationInterface;
use FOS\UserBundle\Model\User;


/**
 * Automatically sets the lastUpdatedDate and lastUpdatedBy fields of entities that are
 * about to be persisted or updated. Entities must implement the @see UpdateInformationInterface
 * for this to work.
 */
class UpdateInformationListener
{
    protected $container;

    /**
     * @param ContainerInterface $container - the DI container used to fetch the current user at a later point
     */
    public function __construct(ContainerInterface $container)
    {
        // Note: it is not possible to directly inject the security.context here since that would
        // introduce a circular dependency
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->setUpdateInformation($event->getEntity());
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $this->setUpdateInformation($event->getEntity());
    }

    /**
     * Sets the lastUpdatedDate and lastUpdatedBy fields of the given entity if it implements the @see UpdateInformationInterface
     * interface
     *
     * @param object $entity - the entity to update
     */
    public function setUpdateInformation($entity)
    {
        if ($entity instanceof UpdateInformationInterface)
        {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $uid = ($user instanceof User ? $user->getId() : -1);

            $entity->setLastUpdatedDate(new \DateTime());
            $entity->setLastUpdatedBy($uid);
        }
    }
}
