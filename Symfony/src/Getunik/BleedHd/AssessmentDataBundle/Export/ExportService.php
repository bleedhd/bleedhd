<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;
use Getunik\BleedHd\AssessmentDataBundle\Handler\QuestionnaireHandler;
use Getunik\BleedHd\AssessmentDataBundle\Handler\ResponseHandler;
use Symfony\Component\Intl\Exception\NotImplementedException;


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

	public function export($exportsPath, $settings)
	{
		// generate a random file name and make sure it only contains "nice" characters
		$id = preg_replace('/[+\/=]/', '', base64_encode(random_bytes(16)));
		$path = $exportsPath . '/' . $id;
		$fileHandle = fopen($path, 'w');

		if (count($settings['typeMap']) === 1) {
			$currentMapping = reset($settings['typeMap']);
			$assessmentType = $currentMapping['assessmentType'];
			$exportType = $currentMapping['export'];
			$filter = new AssessmentFilter($this->assessmentHandler);

			$this->exportSingle($fileHandle, $filter, $assessmentType, $exportType);

			fclose($fileHandle);

			return [
				'id' => $id,
				'name' => implode('-', [$settings['baseName'], $assessmentType, $exportType]) . '.csv',
			];
		} else {
			throw new NotImplementedException('batch export not yet implemented');
		}
	}

	private function exportSingle($fileHandle, AssessmentFilter $filter, $assessmentType, $exportType)
	{
		$config = ExportConfig::load($this->exportConfigPath, $assessmentType, $exportType);
		$table = new Table($config);

		$questionnaire = $this->questionnaireHandler->getQuestionnaireByName($assessmentType);
		$iterator = new AssessmentContextIterator($filter->getAssessments($assessmentType), $questionnaire, $this->responseHandler);

		$table->generate($fileHandle, $iterator);
	}

	private function exportBatch()
	{}

	private function verifySettings($settings)
	{
		if (!isset($settings['baseName'])) {
			throw new \Exception('Missing "baseName" in export configuration');
		}

		if (!isset($settings['filters']) || !is_array($settings['filter'])) {
			throw new \Exception('Missing "filters" in export configuration');
		}

		if (!isset($settings['typeMap']) || !is_array($settings['typeMap'])) {
			throw new \Exception('Missing "typeMap" in export configuration');
		}
	}
}
