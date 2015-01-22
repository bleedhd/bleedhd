<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus;


/**
 * PatientHandler
 */
class PatientStatusHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus';

    private $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);
    }

    public function save(PatientStatus $status)
    {
        $this->repository->save($status, true);
    }

    public function update(PatientStatus $status)
    {
        return $this->repository->update($status);
    }

    public function getPatientStatuses($patientId)
    {
        return $this->repository->findBy(array('patientId' => $patientId));
    }
}
