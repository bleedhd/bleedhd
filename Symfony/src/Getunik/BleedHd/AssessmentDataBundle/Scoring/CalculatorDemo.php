<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorDemo extends CalculatorBase
{
	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);
		$this->score->trueCount = 0;
		$this->score->grade = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		$result = $question->getResult();
		if ($result->hasValue() && $result->getValue() === true)
		{
			$this->score->trueCount++;
		}

		foreach ($scoreMappings as $questionMapping)
		{
			$this->logger->info("question " . $questionMapping->getSlug()->getFull());
			$this->logger->info("  value: " . json_encode($questionMapping->getValue()));
			$this->logger->info("  score relevant: " . ($questionMapping->hasConfig() ? 'true' : 'false'));

			if ($questionMapping->hasConfig())
			{
				$config = $questionMapping->getConfig();
				$this->logger->info("  scoring: " . $config['grade']);

				$this->score->grade = max($this->score->grade, $config['grade']);
			}

			foreach ($questionMapping->getChildren() as $supplementMapping)
			{
				$this->logger->info("    supplement " . $supplementMapping->getSlug()->getFull());
				$this->logger->info("      value: " . json_encode($supplementMapping->getValue()));
				$this->logger->info("      score relevant: " . ($supplementMapping->hasConfig() ? 'true' : 'false'));

				if ($supplementMapping->hasConfig())
				{
					$config = $supplementMapping->getConfig();
					$this->logger->info("      scoring: " . $config['grade']);

					$this->score->grade = max($this->score->grade, $config['grade']);
				}
			}
		}
	}
}
