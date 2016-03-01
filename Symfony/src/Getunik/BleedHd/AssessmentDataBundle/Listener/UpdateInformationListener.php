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
use Getunik\BleedHd\AssessmentDataBundle\Entity\UpdateInformationInterface;
use FOS\UserBundle\Model\User;


/**
 * Automatically sets the lastUpdatedDate and lastUpdatedBy fields of entities that are
 * about to be persisted or updated. Entities must implement the @see UpdateInformationInterface
 * for this to work.
 */
class UpdateInformationListener
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
            $token = $this->tokenInterface->getToken();
            if (!empty($token))
            {
                $user = $this->tokenInterface->getToken()->getUser();
                $uid = ($user instanceof User ? $user->getId() : -1);

                $entity->setLastUpdatedDate(new \DateTime());
                $entity->setLastUpdatedBy($uid);
            }
        }
    }
}
