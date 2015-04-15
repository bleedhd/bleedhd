<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdOrganScoring extends CalculatorBase
{
	const SCORE_NONE = 'No chronic GvHD';
	const SCORE_MILD = 'Mild chronic GvHD';
	const SCORE_MODERATE = 'Moderate chronic GvHD';
	const SCORE_SEVERE = 'Severe chronic GvHD';

	public static $VALUE_MAP = array(self::SCORE_NONE, self::SCORE_MILD, self::SCORE_MODERATE, self::SCORE_SEVERE);

	public function __construct(LoggerInterface $logger)
	{
		parent::__construct($logger);

		$this->score->category = new \stdClass();
	}

	protected function accumulateMapping(ScoreMapping $mapping)
	{
		$config = $mapping->getConfig();
		$value = $mapping->getValue();

		// counting of 'diagnostic' / 'distinctive' types
		if (isset($config['category']))
		{
			if (!isset($this->score->category->{$config['category']}))
			{
				$this->score->category->{$config['category']} = array(
					'value' => 0,
					'override' => NULL,
				);
			}

			$cat = &$this->score->category->{$config['category']};

			if (isset($config['nongvhd']))
			{
				if ($config['nongvhd'] == $value)
				{
					$this->logger->info(" => overriding " . $config['category'] . " score: nongvhd");
					$cat['override'] = 'nongvhd';
				}
			}
			else
			{
				if (isset($config['value']))
				{
					$orig = $value;
					$value = $this->getRangeValue($config['value'], $orig);
					$this->logger->info(" => mapping range: " . $orig . " -> " . $value);
				}

				if (isset($config['organ']) && $config['organ'] === true)
				{
					$cat['organ'] = true;
				}

				if (isset($config['priority']))
				{
					if (!isset($cat['priority']) || $config['priority'] > $cat['priority'])
					{
						$this->logger->info(" => scoring with priority override: " . $config['category'] . " with " . $value);
						$cat['value'] = $value;
						$cat['priority'] = $config['priority'];
					}
					else if ($cat['priority'] == $config['priority'])
					{
						$this->logger->info(" => equal priority value (" . $config['priority'] . "): " . $value);
						// score maximizing with equal priority
						$this->logger->info(" => scoring (max): " . $config['category'] . " with " . $value);
						$cat['value'] = max($cat['value'], $value);
					}
					else
					{
						$this->logger->info(" => higher priority value already present for " . $config['category'] . "; ignoring value: " . $value);
						// skip all the rest (in particular the bumping which could theoretically double-bump)
						return;
					}
				}
				else
				{
					// score maximizing
					$this->logger->info(" => scoring (max): " . $config['category'] . " with " . $value);
					$cat['value'] = max($cat['value'], $value);
				}

				// bump any _non-zero_ value up by the bump config value
				if (isset($config['bump']))
				{
					$cat['bump'] = $cat['value'] + ($cat['value'] === 0 ? 0 : $config['bump']);
				}
			}
		}
	}

	protected function finish()
	{
		// mapping of severity score to number of organs with that score
		$severity = array(
			0 => 0,
			1 => 0,
			2 => 0,
			3 => 0,
		);

		$categories = get_object_vars($this->score->category);
		foreach ($categories as $name => $cat)
		{
			if (empty($cat['override']) && !empty($cat['organ']))
			{
				// use bumped values for global scoring
				$val = (isset($cat['bump']) ? $cat['bump'] : $cat['value']);
				$severity[$val]++;
			}
		}

		$this->score->severity = $severity;
		$this->score->value = $this->getGlobalScore();
		$this->score->total = self::$VALUE_MAP[$this->score->value];
	}

	protected function getRangeValue($ranges, $value)
	{
		foreach ($ranges as $segment)
		{
			$range = $segment['range'];
			$result = $segment['value'];

			if (count($range) === 0 || ($range[0] <= $value && $value < $range[1]))
			{
				return $result;
			}
		}

		return NULL;
	}

	protected function getGlobalScore()
	{
		// the special handling of the 'lungs' score is already included through the 'bump' configuration
		// (regular lung score is incremented by one for non-zero values)
		if ($this->score->severity[3] > 0)
			return 3;

		if ($this->score->severity[2] > 0 || $this->score->severity[1] >= 3)
			return 2;

		if ($this->score->severity[1] > 0)
			return 1;

		return 0;
	}
}
