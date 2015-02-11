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
		if ($assessmentType === 'gvhd-features')
		{
			$name = 'Getunik\BleedHd\AssessmentDataBundle\Scoring\CalculatorGvhdFeatures';
		}
		else if ($assessmentType === 'gvhd-first-diagnosis')
		{
			$name = 'Getunik\BleedHd\AssessmentDataBundle\Scoring\CalculatorGvhdFirstDiagnosis';
		}
		else
		{
			$name = 'Getunik\BleedHd\AssessmentDataBundle\Scoring\Calculator' . ucfirst($assessmentType);
		}

		return new $name($this->logger);
	}
}
