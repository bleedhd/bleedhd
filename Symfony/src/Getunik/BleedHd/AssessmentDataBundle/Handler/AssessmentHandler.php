<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;



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

    public function save(Assessment $assessment)
    {
        $this->repository->save($assessment);
    }

    public function update(Assessment $assessment)
    {
        return $this->repository->merge($assessment);
    }
}
