<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorDemo extends CalculatorBase
{
	public function __construct()
	{
		parent::__construct();
		$this->score->trueCount = 0;
	}

	protected function accumulate(Question $question)
	{
		$result = $question->getResult();
		if ($result->hasValue() && $result->getValue() === true)
		{
			$this->score->trueCount++;
		}
	}
}
