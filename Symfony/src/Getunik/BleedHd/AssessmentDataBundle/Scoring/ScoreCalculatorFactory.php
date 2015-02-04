<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;


class ScoreCalculatorFactory
{
	private $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
		$this->logger->info('testing', array('bla', 42));
	}

	public function create($assessmentType)
	{
		$name = 'Getunik\BleedHd\AssessmentDataBundle\Scoring\Calculator' . ucfirst($assessmentType);

		return new $name($this->logger);
	}
}
