<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorBleedingFeatures extends CalculatorMap
{
	const SCORE_PRESENT = 'Bleeding present';
	const SCORE_NOT_PRESENT = 'No Bleeding present';

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
	}
}
