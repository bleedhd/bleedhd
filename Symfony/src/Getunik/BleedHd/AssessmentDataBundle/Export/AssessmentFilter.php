<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

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

	public function __construct(AssessmentHandler $assessmentHandler)
	{
		$this->assessmentHandler = $assessmentHandler;
	}

	/**
	 * @param $assessmentType string the type of assessment that should be retrieved
	 * @return array
	 */
	public function getAssessments($assessmentType)
	{
		return $this->assessmentHandler->getPatientAssessments(5);
	}
}
