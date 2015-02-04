<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


/**
 * CalculatorBase
 */
abstract class CalculatorBase implements ScoreCalculatorInterface
{
	protected $result;
	protected $score;
	protected $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;

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

	protected function accumulate(Question $question, array $scoreMappings)
	{
		foreach ($scoreMappings as $questionMapping)
		{
			$this->logger->info("question " . $questionMapping->getSlug()->getFull());
			$this->logger->info("  value: " . json_encode($questionMapping->getValue()));
			$this->logger->info("  score relevant: " . ($questionMapping->hasConfig() ? 'true' : 'false'));

			if ($questionMapping->hasConfig())
			{
				$this->accumulateMapping($questionMapping);
			}

			foreach ($questionMapping->getChildren() as $supplementMapping)
			{
				$this->logger->info("    supplement " . $supplementMapping->getSlug()->getFull());
				$this->logger->info("      value: " . json_encode($supplementMapping->getValue()));
				$this->logger->info("      score relevant: " . ($supplementMapping->hasConfig() ? 'true' : 'false'));

				if ($supplementMapping->hasConfig())
				{
					$this->accumulateMapping($supplementMapping);
				}
			}
		}
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
	}

	protected function getExtractor()
	{
		return new ScoreMappingExtractor();
	}
}
