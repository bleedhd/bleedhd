<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorWho extends CalculatorBase
{
	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);
		$this->score->trueCount = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		//$result = $question->getResult();
	}
}
