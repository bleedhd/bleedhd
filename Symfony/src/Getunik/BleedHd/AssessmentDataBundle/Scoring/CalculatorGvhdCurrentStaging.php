<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdCurrentStaging extends CalculatorBase
{
	private static $SPECIAL_ORGANS = array('lungs');

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
					if (isset($cat['priority']) && $cat['priority'] > $config['priority'])
					{
						$this->logger->info(" => higher priority value already present for " . $config['category'] . "; ignoring value: " . $value);
						return;
					}
					else if (isset($cat['priority']) && $cat['priority'] == $config['priority'])
					{
						$this->logger->info(" => equal priority value (" . $config['priority'] . "): " . $value);
					}
					else
					{
						$this->logger->info(" => scoring with priority override: " . $config['category'] . " with " . $value);
						$cat['value'] = $value;
						$cat['priority'] = $config['priority'];
						return;
					}
				}

				// score maximizing
				$this->logger->info(" => scoring (max): " . $config['category'] . " with " . $value);
				$cat['value'] = max($cat['value'], $value);
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
			if (empty($cat['override']) && !empty($cat['organ']) && !in_array($name, self::$SPECIAL_ORGANS))
			{
				$severity[$cat['value']]++;
			}
		}

		$this->score->severity = $severity;
		$this->score->total = $this->getGlobalScore();
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
		$lung = (isset($this->score->category->lungs) ? $this->score->category->lungs['value'] : 0);

		if ($lung >= 2 || $this->score->severity[3] > 0)
			return 3;

		if ($lung == 1 || $this->score->severity[2] > 0 || $this->score->severity[1] >= 3)
			return 2;

		if ($this->score->severity[1] > 0)
			return 1;

		return 0;
	}
}
