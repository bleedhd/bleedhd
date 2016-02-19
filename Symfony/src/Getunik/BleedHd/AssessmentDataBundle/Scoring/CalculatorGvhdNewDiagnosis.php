<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdNewDiagnosis extends CalculatorAgvhdFollowUp
{
	const STATUS_POSITIVE = 'positive';
	const STATUS_PENDING = 'pending';
	const STATUS_NEGATIVE = 'negative';

	const DELAY_NORMAL = 'normal';
	const DELAY_LATE_ONSET = 'lateonset';

	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);

		// 'GVHD present', 'No GVHD present'
		$this->score->missingChronic = 0;
		$this->score->diagnostic = 0;
		$this->score->distinctive = 0;
		$this->score->distinctiveDependent = 0;
		$this->score->distinctivePositive = 0;
		$this->score->distinctivePending = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		$result = $question->getResult();
		$questionDefinition = $question->getQuestion();

		if (isset($questionDefinition['score']))
		{
			// counting of unanswered questions that are relevant for the chronic section
			if (isset($questionDefinition['score']['type']) && $result->isUnanswered())
			{
				$this->logger->info("unanswered score relevant question " . $question->getSlug()->getFull());
				$this->score->missingChronic++;
			}
		}

		// scoring of acute part is already implemented in CalculatorAgvhdFollowUp
		parent::accumulate($question, $scoreMappings);
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
		$config = $mapping->getConfig();
		$value = $mapping->getValue();

		// counting of 'diagnostic' / 'distinctive' types
		if (isset($config['type']) && !empty($value))
		{
			$this->logger->info(" => scoring: " . $config['type']);
			$this->score->{$config['type']}++;
		}

		// counting confirmation status 'pending' / 'confirmed'
		if (isset($config['status']) && !empty($value))
		{
			$this->logger->info(" => scoring: distinctive " . $config['status']);
			$this->score->{'distinctive' . ucfirst($config['status'])}++;
		}

		// acute delay
		if (isset($config['delay']) && !empty($value))
		{
			$this->logger->info(" => scoring: delay of aGVHD " . $config['delay']);
			$this->score->acuteDelay = $config['delay'];
		}

		// scoring of acute part is already implemented in CalculatorAgvhdFollowUp
		parent::accumulateMapping($mapping);
	}

	protected function finish()
	{
		$this->score->chronic = $this->getChronicScore();
		$this->score->acute = $this->getAcuteScore();

		$this->score->total = $this->getTotalScore();
	}

	/**
	 * @return string - self::STATUS_* depending on the presence of diagnostic and (confirmed) distinctive signs
	 */
	protected function getChronicScore()
	{
		if ($this->score->diagnostic > 0 || $this->score->distinctivePositive > 0)
		{
			return self::STATUS_POSITIVE;
		}

		// BBSBLEED-245
		// Special handling for lung special cases "BOS" and "air trapping on CT"
		// this is modeled with the special distinctiveDependent score type which functionally
		// acts like a distinctive sign that can only be confirmed by another _real_ distinctive
		// sign that is at least pending. Since confirmed distinctive signs already trigger
		// STATUS_POSITIVE on their own, this rule only considers pending distinctive signs
		// Note:
		//   The aspect of "distinctive sign in _another organ_" is not modeled explicity;
		//   instead the assumption is that if it was possible (hypothetical) to have a _real_
		//   distinctive sign in the lung, then this sign would also suffice as implicit
		//   confirmation.
		if ($this->score->distinctiveDependent > 0 && $this->score->distinctivePending > 0)
		{
			return self::STATUS_POSITIVE;
		}

		if ($this->score->missingChronic > 0 || $this->score->distinctivePending > 0)
		{
			return self::STATUS_PENDING;
		}

		return self::STATUS_NEGATIVE;
	}

	/**
	 * @return string - the score string describing the acute/chronic GVHD expression
	 */
	protected function getTotalScore()
	{
		if ($this->score->chronic === self::STATUS_PENDING)
			return 'pending';

		if ($this->score->chronic === self::STATUS_POSITIVE)
			return ($this->score->acute === self::STATUS_PENDING ? 'chronic' : ($this->score->acute > 0 ? 'overlap cGVHD' : 'classic cGVHD'));

		if ($this->score->acute === 0)
			return 'no GVHD';

		if ($this->score->acute === self::STATUS_PENDING || !isset($this->score->acuteDelay))
			return 'pending';

		return $this->score->acuteDelay === self::DELAY_NORMAL ? 'classic aGVHD' : 'late-onset aGVHD';
	}
}
