<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Controller\ExportDownloadController;
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
	/**
	 * @var string
	 */
	private $exportStorageDir;

	/**
	 * ExportService constructor.
	 * @param AssessmentHandler $assessmentHandler
	 * @param QuestionnaireHandler $questionnaireHandler
	 * @param ResponseHandler $responseHandler
	 * @param $exportConfigPath string path to the export configuration files
	 * @param $exportStorageDir string path to the directory where generated export files will be stored
	 */
	public function __construct(AssessmentHandler $assessmentHandler, QuestionnaireHandler $questionnaireHandler, ResponseHandler $responseHandler, $exportConfigPath, $exportStorageDir)
	{
		$this->assessmentHandler = $assessmentHandler;
		$this->questionnaireHandler = $questionnaireHandler;
		$this->responseHandler = $responseHandler;
		$this->exportConfigPath = $exportConfigPath;
		$this->exportStorageDir = $exportStorageDir;
	}

	/**
	 * Generates an export file for the given export settings and returns an array with the generated file's ID and
	 * name which can be used to download the file via the @see ExportDownloadController.
	 *
	 * @param $settings array export settings structure
	 * @return array information about the generated export ('id' and 'name')
	 */
	public function export($settings)
	{
		$this->verifySettings($settings);

		// generate a random file name and make sure it only contains "nice" characters
		$id = preg_replace('/[+\/=]/', '', base64_encode(random_bytes(16)));
		$path = $this->exportStorageDir . '/' . $id;
		$fileHandle = fopen($path, 'w');

		if (count($settings['typeMap']) === 1) {
			$currentMapping = reset($settings['typeMap']);
			$assessmentType = $currentMapping['assessmentType'];
			$exportType = $currentMapping['export'];
			$filter = new AssessmentFilter($this->assessmentHandler, $settings['filters']);

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

	private function exportBatch($fileHandle, AssessmentFilter $filter, $typeMap)
	{
		// not yet implemented
	}

	private function verifySettings($settings)
	{
		if (!isset($settings['baseName'])) {
			throw new \Exception('Missing "baseName" in export configuration');
		}

		if (!isset($settings['filters']) || !is_array($settings['filters'])) {
			throw new \Exception('Missing "filters" in export configuration');
		}

		if (!isset($settings['typeMap']) || !is_array($settings['typeMap'])) {
			throw new \Exception('Missing "typeMap" in export configuration');
		}
	}
}
