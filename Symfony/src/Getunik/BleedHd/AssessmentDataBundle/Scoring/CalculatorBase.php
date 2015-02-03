<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


/**
 * CalculatorBase
 */
abstract class CalculatorBase implements ScoreCalculatorInterface
{
	protected $result;
	protected $score;

	public function __construct()
	{
		$this->result = new \stdClass();

		$this->result->stats = new \stdClass();
		$this->result->stats->questions = 0;
		$this->result->stats->unanswered = 0;
		$this->result->stats->answered = 0;

		$this->result->score = new \stdClass();
		$this->score = $this->result->score;
	}

	public function run(AssessmentContext $context)
	{
		$extractor = $this->getExtractor();
		foreach ($context->getQuestions() as $question)
		{
			$scoreMappings = $extractor->extract($question);
			$this->accumulateStats($question);
			$this->accumulate($question, $scoreMappings);
		}

		return $this;
	}

	public function getResult()
	{
		return $this->result;
	}

	protected function accumulateStats(Question $question)
	{
		$this->result->stats->questions++;

		$result = $question->getResult();

		// there are various ways to not actually answer a question (multiple meta answers)
		// and only the ones left untouched (not yet answered) are counted as unanswered.
		if ($result->isUnanswered())
		{
			$this->result->stats->unanswered++;
		}
		else if ($result->hasValue())
		{
			$this->result->stats->answered++;
		}
	}

	protected abstract function accumulate(Question $question, array $scoreMappings);

	protected function getExtractor()
	{
		return new ScoreMappingExtractor();
	}
}
