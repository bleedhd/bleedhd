<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdTherapyResponse extends CalculatorBase
{
	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);
	}
}
