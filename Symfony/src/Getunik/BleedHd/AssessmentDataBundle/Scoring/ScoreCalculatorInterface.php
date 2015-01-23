<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;


interface ScoreCalculatorInterface
{
	public function run(AssessmentContext $context);
}
