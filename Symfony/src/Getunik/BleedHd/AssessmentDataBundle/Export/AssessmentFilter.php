<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;

class AssessmentFilter
{
	private $assessmentHandler;

	public function __construct(AssessmentHandler $assessmentHandler)
	{
		$this->assessmentHandler = $assessmentHandler;
	}

	/**
	 * @return array
	 */
	public function getAssessments($assessmentType)
	{
		return $this->assessmentHandler->getPatientAssessments(5);
	}
}
