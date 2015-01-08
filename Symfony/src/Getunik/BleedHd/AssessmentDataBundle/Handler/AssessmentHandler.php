<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;



/**
 * AssessmentHandler
 */
class AssessmentHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment';

    private $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);
    }

    public function getPatientAssessments($patientId)
    {
        return $this->repository->findBy(array('patientId' => $patientId));
    }
}
