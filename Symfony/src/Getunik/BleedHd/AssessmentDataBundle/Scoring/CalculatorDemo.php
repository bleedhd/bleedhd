<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorDemo extends CalculatorBase
{
	public function __construct()
	{
		parent::__construct();
		$this->score->trueCount = 0;
		$this->score->grade = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		$result = $question->getResult();
		if ($result->hasValue() && $result->getValue() === true)
		{
			$this->score->trueCount++;
		}

		foreach ($scoreMappings as $questionMapping)
		{
			//echo "question " . $questionMapping->getSlug()->getFull() . "\n";
			//echo "  value: " . json_encode($questionMapping->getValue()) . "\n";

			if ($questionMapping->hasConfig())
			{
				$config = $questionMapping->getConfig();
				//echo "  scoring: " . $config['grade']. "\n";

				$this->score->grade = max($this->score->grade, $config['grade']);
			}

			foreach ($questionMapping->getChildren() as $supplementMapping)
			{
				//echo "    supplement " . $supplementMapping->getSlug()->getFull() . "\n";
				//echo "      value: " . json_encode($supplementMapping->getValue()) . "\n";

				if ($supplementMapping->hasConfig())
				{
					$config = $supplementMapping->getConfig();
					//echo "      scoring: " . $config['grade']. "\n";

					$this->score->grade = max($this->score->grade, $config['grade']);
				}
			}
		}
	}
}
