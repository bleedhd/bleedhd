<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


/**
 * The scoring for aGVHD follow-up is a subset of the scoring of the
 * @see CalculatorGvhdNewDiagnosis
 */
class CalculatorAgvhdFollowUp extends CalculatorBase
{
	const STATUS_PENDING = 'pending';

	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);

		$this->score->missingAcute = 0;
	}

	protected function accumulate(Question $question, array $scoreMappings)
	{
		$result = $question->getResult();
		$questionDefinition = $question->getQuestion();

		if (isset($questionDefinition['score']))
		{
			// counting of unanswered questions that are relevant for the acute section
			if (isset($questionDefinition['score']['acute']) && $result->isUnanswered())
			{
				$this->logger->info("unanswered score relevant question " . $question->getSlug()->getFull());
				$this->score->missingAcute++;
			}
		}

		parent::accumulate($question, $scoreMappings);
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
		$config = $mapping->getConfig();
		$value = $mapping->getValue();

		// grading acute by organ
		if (isset($config['acute']) && !empty($value))
		{
			$this->logger->info(" => scoring: acute " . $config['acute'] . " with " . $value);
			$this->score->{'acute' . ucfirst($config['acute'])} = $value;
		}
	}

	protected function finish()
	{
		$this->score->acute = $this->getAcuteScore();

		$this->score->total = $this->score->acute;
	}

	/**
	 * @see CalculatorGvhdNewDiagnosis::getAcuteScore
	 * @return - a number in the range [0, 4] or the value self::STATUS_PENDING; 0 indicates no acute symptoms, while 1-4 indicate severity
	 */
	protected function getAcuteScore()
	{
		if ($this->score->missingAcute > 0)
			return self::STATUS_PENDING;

		$skin = $this->safeGetScore('acuteSkin');
		$liver = $this->safeGetScore('acuteLiver');
		$gut = $this->safeGetScore('acuteGut');

		if ($skin == 4 || $liver == 4)
			return 4;

		if ($liver == 2 || $liver == 3 || $gut == 2 || $gut == 3 || $gut == 4)
			return 3;

		if ($skin == 3 || $liver == 1 || $gut == 1)
			return 2;

		if ($skin == 1 || $skin == 2)
			return 1;

		return 0;
	}

	protected function safeGetScore($name)
	{
		return isset($this->score->{$name}) ? $this->score->{$name} : -1;
	}
}
