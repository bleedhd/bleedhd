<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;


/**
 * Class Table
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 * 
 * Represents a CSV table with a fixed set of columns defined by the @see ExportConfig.
 */
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

	public function generate($fileHandle, AssessmentContextIterator $assessments)
	{
		$columns = $this->config->getColumns();

		$headers = array_map(function ($col) {
			/** @var ColumnDefinition $col */
			return $col->getLabel();
		}, $columns);

		fputcsv($fileHandle, $headers);

		/**
		 * @var Assessment $assessment
		 */
		foreach ($assessments as $context) {
			/** @var AssessmentContext $context */
			$row = [];

			foreach ($columns as $col) {
				$row[] = $col->extract($context);
			}

			fputcsv($fileHandle, $row);
		}
	}
}