<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;

class ExportService
{
	private $assessmentHandler;
	private $exportConfigPath;

	public function __construct(AssessmentHandler $assessmentHandler, $exportConfigPath)
	{
		$this->assessmentHandler = $assessmentHandler;
		$this->exportConfigPath = $exportConfigPath;
	}

	public function export($fileHandle) {
		$data = [
			['Header1', 'Header2'],
			['alpha', 'beta \' with apostrophe'],
			['one', 'two " with quote'],
			['first', 'second with space'],
		];

		$assessmentType = 'who';
		$exportType = 'default';
		$filter = new AssessmentFilter($this->assessmentHandler);

		$filePath = $this->exportConfigPath . '/' . $assessmentType . '/' . $exportType .  '.yaml';
		if (!file_exists($filePath)) {
			throw new \Exception('Cannot find export configuration "' . $exportType . '" for assessment type "' . $assessmentType . '"');
		}

		$config = new ExportConfig($filePath);

		$table = new Table($config);

		$table->generate($fileHandle, $filter->getAssessments($assessmentType));
	}
}
