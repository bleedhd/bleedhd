<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdFirstDiagnosis extends CalculatorBase
{
	const STATUS_POSITIVE = 'positive';
	const STATUS_PENDING = 'pending';
	const STATUS_NEGATIVE = 'negative';

	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);

		// 'GvHD present', 'No GvHD present'
		$this->score->missingChronic = 0;
		$this->score->diagnostic = 0;
		$this->score->distinctive = 0;
		$this->score->distinctivePositive = 0;
		$this->score->distinctivePending = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		$result = $question->getResult();
		$questionDefinition = $question->getQuestion();

		if (isset($questionDefinition['score']) && isset($questionDefinition['score']['type']))
		{
			// counting of unanswered questions that are relevant for the chronic section
			if ($result->isUnanswered())
			{
				$this->logger->info("unanswered score relevant question " . $question->getSlug()->getFull());
				$this->score->missingChronic++;
			}
		}

		parent::accumulate($question, $scoreMappings);
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
		$config = $mapping->getConfig();

		// counting of 'diagnostic' / 'distinctive' types
		if (isset($config['type']))
		{
			$this->logger->info(" => scoring: " . $config['type']);
			$this->score->{$config['type']}++;
		}

		// counting confirmation status 'pending' / 'confirmed'
		if (isset($config['status']))
		{
			$this->logger->info(" => scoring: distinctive " . $config['status']);
			$this->score->{'distinctive' . ucfirst($config['status'])}++;
		}
	}

	protected function finish()
	{
		$this->score->chronic = $this->getChronicScore();
	}

	protected function getChronicScore()
	{
		if ($this->score->diagnostic > 0 || $this->score->distinctivePositive > 0)
		{
			return self::STATUS_POSITIVE;
		}

		if ($this->score->missingChronic > 0 || $this->score->distinctivePending > 0)
		{
			return self::STATUS_PENDING;
		}

		return self::STATUS_NEGATIVE;
	}
}
