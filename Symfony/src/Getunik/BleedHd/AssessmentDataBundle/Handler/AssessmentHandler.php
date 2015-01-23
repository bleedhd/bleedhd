<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;


/**
 * AssessmentHandler
 */
class AssessmentHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment';

    private $repository;
    private $responseHandler;
    private $questionnaireHandler;

    public function __construct(ManagerRegistry $managerRegistry, ResponseHandler $responseHandler, QuestionnaireHandler $questionnaireHandler)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);

        $this->responseHandler = $responseHandler;
        $this->questionnaireHandler = $questionnaireHandler;
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

    public function updateScore(Assessment $assessment)
    {
        $responses = $this->responseHandler->getAssessmentResponses($assessment->getId());
        $questionnaire = $this->questionnaireHandler->getQuestionnaireByName($assessment->getQuestionnaire());

        $context = new AssessmentContext($assessment, $questionnaire, $responses);
    }
}
