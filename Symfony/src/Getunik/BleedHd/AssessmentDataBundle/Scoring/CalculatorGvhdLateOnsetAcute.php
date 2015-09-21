<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdLateOnsetAcute extends CalculatorMap
{
	const SCORE_PERSISTENT = 'persistent aGVHD';
	const SCORE_RECURRENT = 'recurrent aGVHD';

	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);
	}

	protected function finish()
	{
		if (isset($this->score->interval))
		{
			$this->score->total = ($this->score->interval === 'persistent' ? self::SCORE_PERSISTENT : self::SCORE_RECURRENT);
		}
	}
}
