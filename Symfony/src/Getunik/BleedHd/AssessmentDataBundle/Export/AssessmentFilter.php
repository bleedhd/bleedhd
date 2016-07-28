<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;


/**
 * Class AssessmentFilter
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Generates a list of assessments based on complex filter criteria and grouped by assessment type.
 */
class AssessmentFilter
{
	private $assessmentHandler;
	private $filterSpec;

	/**
	 * AssessmentFilter constructor.
	 * @param AssessmentHandler $assessmentHandler
	 * @param $filterSpec
	 */
	public function __construct(AssessmentHandler $assessmentHandler, $filterSpec)
	{
		$this->assessmentHandler = $assessmentHandler;
		$this->filterSpec = $filterSpec;
	}

	/**
	 * @param $questionnaire string the questionnaire / type of assessment that should be retrieved
	 * @return Assessment[]
	 */
	public function getAssessments($questionnaire)
	{
		return $this->assessmentHandler->getFilteredAssessments($this->filterSpec, $questionnaire);
	}
}
