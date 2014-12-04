<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class FlushDataListener
{
    protected $entityManager;

    public function __construct(ContainerInterface $container, $entityManagerName)
    {
        $this->entityManager = $container->get($entityManagerName);
    }

    public function onFihishRequest(FilterResponseEvent $event)
    {
        $this->entityManager->flush();
    }
}
