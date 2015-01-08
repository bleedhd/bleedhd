<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;



/**
 * ResponseHandler
 */
class ResponseHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\Response';

    private $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);
    }

    public function getAssessmentResponses($assessmentId)
    {
        return $this->repository->findBy(array('assessmentId' => $assessmentId));
    }

    public function save(Response $response)
    {
        $this->repository->save($response);
    }

    public function update(Response $response)
    {
        return $this->repository->merge($response);
    }
}
