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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


/**
 * A kernel.response event listener that explicitly flushes the configured entity manager
 * when the request is finished
 */
class FlushDataListener
{
    protected $entityManager;

    /**
     * @param ContainerInterface $container
     * @param string $entityManagerName - the name of the entity manager that should be flushed on kernel.response
     */
    public function __construct(ContainerInterface $container, $entityManagerName)
    {
        $this->entityManager = $container->get($entityManagerName);
    }

    /**
     * Flushes the configured entity manager
     *
     * @param FilterResponseEvent $event
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $this->entityManager->flush();
    }
}
