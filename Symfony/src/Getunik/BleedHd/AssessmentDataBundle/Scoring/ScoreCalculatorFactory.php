<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;


class ScoreCalculatorFactory
{
	public static function create($assessmentType)
	{
		$name = 'Getunik\BleedHd\AssessmentDataBundle\Scoring\Calculator' . ucfirst($assessmentType);

		return new $name();
	}
}
