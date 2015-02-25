<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Psr\Log\LoggerInterface;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


class CalculatorGvhdCurrentStaging extends CalculatorBase
{
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

				$this->logger->info(" => scoring: " . $config['category'] . " with " . $value);
				$cat['value'] = max($cat['value'], $value);
			}
		}
	}

	protected function finish()
	{
		//$this->score->total = $this->getTotalScore();
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
}
