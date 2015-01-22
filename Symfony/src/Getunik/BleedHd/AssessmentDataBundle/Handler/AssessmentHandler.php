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

    public function save(Assessment $assessment)
    {
        $this->repository->save($assessment, true);
    }

    public function update(Assessment $assessment)
    {
        return $this->repository->update($assessment);
    }

    public function getPatientAssessments($patientId)
    {
        return $this->repository->findBy(array('patientId' => $patientId));
    }
}
