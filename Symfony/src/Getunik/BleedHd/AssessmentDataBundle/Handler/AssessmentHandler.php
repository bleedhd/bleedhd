<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreCalculatorFactory;


/**
 * AssessmentHandler
 */
class AssessmentHandler
{
    public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment';

    private $repository;
    private $responseHandler;
    private $questionnaireHandler;
    private $scoreCalculatorFactory;

    public function __construct(ManagerRegistry $managerRegistry, ResponseHandler $responseHandler, QuestionnaireHandler $questionnaireHandler, ScoreCalculatorFactory $scoreCalculatorFactory)
    {
        $this->repository = $managerRegistry
                            ->getManagerForClass(self::$entityType)
                            ->getRepository(self::$entityType);

        $this->responseHandler = $responseHandler;
        $this->questionnaireHandler = $questionnaireHandler;
        $this->scoreCalculatorFactory = $scoreCalculatorFactory;
    }

    public function save(Assessment $assessment)
    {
        $this->repository->save($assessment, true);
    }

    public function update(Assessment $assessment)
    {
        return $this->repository->update($assessment);
    }

    public function delete(Assessment $assessment)
    {
        $assessment->setIsDeleted(true);
        return $this->update($assessment);
    }

    public function restore(Assessment $assessment)
    {
        $assessment->setIsDeleted(false);
        return $this->update($assessment);
    }

    public function getPatientAssessments($patientId)
    {
        return $this->repository->findBy(array('patientId' => $patientId, 'isDeleted' => 0));
    }

    public function updateScore(Assessment $assessment)
    {
        $responses = $this->responseHandler->getAssessmentResponses($assessment->getId());
        $questionnaire = $this->questionnaireHandler->getQuestionnaireByName($assessment->getQuestionnaire());

        $context = new AssessmentContext($assessment, $questionnaire, $responses);
        $calculator = $this->scoreCalculatorFactory->create($assessment->getQuestionnaire());

        $result = $calculator->run($context)->getResult();
        $assessment->setResult($result);
        $assessment->setQuestionnaireVersion($context->getQuestionnaireVersion());
        $this->repository->save($assessment, false);

        return $assessment;
    }

    /**
     * Creates an associative array from patient IDs to their overall assessment status. The status indicates whether
     * all of the patients assessments are completed (have a total score) or not.
     *
     * @param array $patientIds - a list of patient IDs
     * @return array - a mapping from patient IDs to their assessment status
     */
    public function getAssessmentProgress(array $patientIds)
    {
        $result = array();
        foreach ($patientIds as $id)
        {
            $result[$id] = array('patient_id' => $id, 'progress' => Assessment::PROGRESS_NONE);
        }

        $assessments = $this->repository->getPatientProgress($patientIds);
        foreach ($assessments as $assessment)
        {
            $patient = &$result[$assessment['patientId']];

            if ($assessment['progress'] === Assessment::PROGRESS_COMPLETE && $patient['progress'] === Assessment::PROGRESS_NONE)
            {
                $patient['progress'] = Assessment::PROGRESS_COMPLETE;
            }
            else if ($assessment['progress'] === Assessment::PROGRESS_TENTATIVE || $assessment['progress'] === NULL)
            {
                $patient['progress'] = Assessment::PROGRESS_TENTATIVE;
            }
        }

        return array_values($result);
    }
}
