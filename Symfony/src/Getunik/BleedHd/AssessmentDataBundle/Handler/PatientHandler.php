<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;


/**
 * PatientHandler
 */
class PatientHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\Patient';

    private $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);
    }

    public function save(Patient $patient)
    {
        $this->repository->save($patient, true);
    }

    public function update(Patient $patient)
    {
        return $this->repository->update($patient);
    }

    public function getAllPatients()
    {
        return $this->repository->findAll();
    }
}
