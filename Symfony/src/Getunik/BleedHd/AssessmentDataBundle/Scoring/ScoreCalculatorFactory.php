<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;


class ScoreCalculatorFactory
{
	private $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function create($assessmentType)
	{
		// Transform the dash (-) separated assessment type name into a CamelCase class name
		$parts = explode('-', $assessmentType);
		array_walk($parts, function (&$element) {
			$element = ucfirst($element);
		});
		$className = 'Getunik\BleedHd\AssessmentDataBundle\Scoring\Calculator' . implode($parts);

		return new $className($this->logger);
	}
}
