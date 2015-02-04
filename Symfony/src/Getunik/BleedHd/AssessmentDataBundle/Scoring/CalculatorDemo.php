<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorDemo extends CalculatorMaxValue
{
	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger, 'grade');
		$this->score->trueCount = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		$result = $question->getResult();
		if ($result->hasValue() && $result->getValue() === true)
		{
			$this->score->trueCount++;
		}

		parent::accumulate($question, $scoreMappings);
	}
}
