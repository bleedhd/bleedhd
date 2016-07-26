<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;

class ExportService
{
	private $assessmentHandler;

	public function __construct(AssessmentHandler $assessmentHandler)
	{
		$this->assessmentHandler = $assessmentHandler;
	}

	public function export($fileHandle) {
		$data = [
			['Header1', 'Header2'],
			['alpha', 'beta \' with apostrophe'],
			['one', 'two " with quote'],
			['first', 'second with space'],
		];

		$filter = new AssessmentFilter($this->assessmentHandler);

		/**
		 * @var Assessment $assessment
		 */
		foreach ($filter->getAssessments() as $assessment) {
			fputcsv($fileHandle, [$assessment->getPatient()->getFirstname(), $assessment->getPatient()->getLastname(), $assessment->getQuestionnaire(), $assessment->getId()]);
		}

		foreach ($data as $line) {
			fputcsv($fileHandle, $line, ',', '"', '\\');
		}
	}
}
