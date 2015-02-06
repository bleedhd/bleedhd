<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorMaxValue extends CalculatorBase
{
	private $valueKey;

	public function __construct(LoggerInterface $logger, $valueKey)
	{
		parent::__construct($logger);

		$this->valueKey = $valueKey;
		$this->score->total = 0;
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
		$config = $mapping->getConfig();
		if (isset($config[$this->valueKey]))
		{
			$this->logger->info(" => scoring: " . $config[$this->valueKey]);
			$this->score->total = max($this->score->total, $config[$this->valueKey]);
		}
	}
}
