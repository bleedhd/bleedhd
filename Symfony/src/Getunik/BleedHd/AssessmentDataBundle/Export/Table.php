<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;

class Table
{
	/**
	 * @var ExportConfig
	 */
	private $config;

	public function __construct(ExportConfig $config)
	{
		$this->config = $config;
	}

	public function generate($fileHandle, array $assessments)
	{
		/**
		 * @var Assessment $assessment
		 */
		foreach ($assessments as $assessment) {
			fputcsv($fileHandle, [$assessment->getPatient()->getFirstname(), $assessment->getPatient()->getLastname(), $assessment->getQuestionnaire(), $assessment->getId()]);
		}
	}
}
