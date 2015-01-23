<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;


/**
 * AssessmentContext
 */
class AssessmentContext
{
    private static $hierarchy = array('chapters', 'sections', 'screens', 'questions');

    private $assessment;
    private $questions = array();

    public function __construct(Assessment $assessment, array $questionnaire, array $responses)
    {
        $this->processQuestionnaire($questionnaire, $responses);
    }

    protected function processQuestionnaire(array $questionnaire, array $responses)
    {
        $responseMap = array();

        foreach ($responses as $response)
        {
            $responseMap[$response->getQuestionSlug()] = $response;
        }

        $this->processHierarchySegment($questionnaire, $responseMap, 0);
    }

    protected function processHierarchySegment(array $segmentData, array $responseMap, $segmentIndex, Slug $slug = NULL)
    {
        $segmentName = self::$hierarchy[$segmentIndex];
        $isLast = ($segmentIndex === count(self::$hierarchy) - 1);

        foreach ($segmentData[$segmentName] as $subSegment)
        {
            $subSlug = new Slug(isset($subSegment['slug']) ? $subSegment['slug'] : NULL, $slug);
            if ($isLast)
            {
                if (isset($subSegment['type']) && $subSegment['type'] === 'multi')
                {
                    $this->processHierarchySegment($subSegment, $responseMap, $segmentIndex, $subSlug);
                }
                else
                {
                    $response = isset($responseMap[$subSlug->getFull()]) ? $responseMap[$subSlug->getFull()] : NULL;
                    $this->questions[] = new Question($subSlug, $subSegment, $response);
                }
            }
            else
            {
                $this->processHierarchySegment($subSegment, $responseMap, $segmentIndex + 1, $subSlug);
            }
        }
    }

    public function getAssessment()
    {
        return $this->assessment;
    }

    public function getQuestions()
    {
        return $this->questions;
    }
}
