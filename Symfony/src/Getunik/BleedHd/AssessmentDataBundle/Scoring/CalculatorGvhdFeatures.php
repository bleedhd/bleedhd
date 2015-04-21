<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdFeatures extends CalculatorMap
{
	const SCORE_PRESENT = 'GVHD present';
	const SCORE_NOT_PRESENT = 'No GVHD present';

	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);
	}

	protected function finish()
	{
		if (isset($this->score->present))
		{
			$this->score->total = ($this->score->present ? self::SCORE_PRESENT : self::SCORE_NOT_PRESENT);
		}
		else
		{
			$this->score->total = NULL;
		}
	}
}
