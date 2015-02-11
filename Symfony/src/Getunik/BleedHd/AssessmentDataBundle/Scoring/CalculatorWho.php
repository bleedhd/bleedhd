<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorWho extends CalculatorMaxValue
{
	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger, 'grade');
	}
}
