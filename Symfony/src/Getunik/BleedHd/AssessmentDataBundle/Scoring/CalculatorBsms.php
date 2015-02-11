<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorBsms extends CalculatorMap
{
	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger, 'grade');

		$this->score->total = 0;
	}

	protected function finish()
	{
		if (isset($this->score->type))
		{
			$this->score->total = $this->score->type;
		}

		if (isset($this->score->subtype))
		{
			$this->score->total .= $this->score->subtype;
		}
	}
}
