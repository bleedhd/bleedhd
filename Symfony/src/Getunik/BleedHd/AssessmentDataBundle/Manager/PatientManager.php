<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;


/**
 * PatientManager
 */
class PatientManager
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
}
