<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;
use Getunik\BleedHd\AssessmentDataBundle\Handler\QuestionnaireHandler;
use Getunik\BleedHd\AssessmentDataBundle\Handler\ResponseHandler;


/**
 * Class ExportService
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 * 
 * This is the driving force behind the data export mechanism. @see ExportService::export
 */
class ExportService
{
	/**
	 * @var AssessmentHandler
	 */
	private $assessmentHandler;
	/**
	 * @var QuestionnaireHandler
	 */
	private $questionnaireHandler;
	/**
	 * @var ResponseHandler
	 */
	private $responseHandler;
	/**
	 * @var string
	 */
	private $exportConfigPath;

	public function __construct(AssessmentHandler $assessmentHandler, QuestionnaireHandler $questionnaireHandler, ResponseHandler $responseHandler, $exportConfigPath)
	{
		$this->assessmentHandler = $assessmentHandler;
		$this->questionnaireHandler = $questionnaireHandler;
		$this->responseHandler = $responseHandler;
		$this->exportConfigPath = $exportConfigPath;
	}

	public function export($fileHandle)
	{
		$assessmentType = 'who';
		$exportType = 'default';
		$questionnaire = $this->questionnaireHandler->getQuestionnaireByName($assessmentType);
		$filter = new AssessmentFilter($this->assessmentHandler);

		$filePath = $this->exportConfigPath . '/' . $assessmentType . '/' . $exportType . '.yaml';
		if (!file_exists($filePath)) {
			throw new \Exception('Cannot find export configuration "' . $exportType . '" for assessment type "' . $assessmentType . '"');
		}

		$config = new ExportConfig($filePath);

		$table = new Table($config);
		$iterator = new AssessmentContextIterator($filter->getAssessments($assessmentType), $questionnaire, $this->responseHandler);

		$table->generate($fileHandle, $iterator);
	}
}
