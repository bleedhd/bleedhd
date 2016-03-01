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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Getunik\BleedHd\AssessmentDataBundle\Entity\CreationInformationInterface;
use FOS\UserBundle\Model\User;


/**
 * Automatically sets the lastUpdatedDate and lastUpdatedBy fields of entities that are
 * about to be persisted or updated. Entities must implement the @see UpdateInformationInterface
 * for this to work.
 */
class CreationInformationListener
{
    protected $tokenInterface;

    /**
     * @param TokenStorageInterface $tokenInterface - the security token interface service
     */
    public function __construct(TokenStorageInterface $tokenInterface)
    {
        $this->tokenInterface = $tokenInterface;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->setCreationInformation($event->getEntity());
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $this->preserveCreationInformation($event->getEntity(), $event->getEntityChangeSet());
    }

    /**
     * Sets the createdDate and createdBy fields of the given entity if it implements the @see CreationInformationInterface
     * interface
     *
     * @param object $entity - the entity to update
     */
    public function setCreationInformation($entity)
    {
        if ($entity instanceof CreationInformationInterface)
        {
            $user = $this->tokenInterface->getToken()->getUser();
            $uid = ($user instanceof User ? $user->getId() : -1);

            $entity->setCreatedDate(new \DateTime());
            $entity->setCreatedBy($uid);
        }
    }

    /**
     * Resets the createdDate and createdBy fields of the given entity if it implements the @see CreationInformationInterface
     * interface
     *
     * @param object $entity - the entity to update
     * @param array $changeset - the current entity changeset
     */
    public function preserveCreationInformation($entity, $changeset)
    {
        if ($entity instanceof CreationInformationInterface)
        {
            if (isset($changeset['createdDate']))
            {
                $entity->setCreatedDate($changeset['createdDate'][0]);
            }

            if (isset($changeset['createdBy']))
            {
                $entity->setCreatedBy($changeset['createdBy'][0]);
            }
        }
    }
}
