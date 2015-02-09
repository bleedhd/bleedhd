<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorMap extends CalculatorBase
{
	private $valueKey;

	public function __construct(LoggerInterface $logger, $valueKey)
	{
		parent::__construct($logger);

		$this->score->total = 0;
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
		$config = $mapping->getConfig();

		if (is_string($config))
		{
			$this->score->{$config} = $mapping->getValue();
		}
		else
		{
			foreach ($config as $key => $value) {
				$this->logger->info(" => scoring: " . $key . " as " . $value);
				$this->score->{$key} = $value;
			}
		}
	}
}
