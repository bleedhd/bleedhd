<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Slug;
use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreMappingExtractor\QuestionAccumulator;
use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreMappingExtractor\SupplementAccumulator;
use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreMappingExtractor\IAccumulator;



/**
 * ScoreMappingExtractor
 */
class ScoreMappingExtractor
{
	protected $questionTypeMap = array(
		'yesno' => 'extractOptionsWithDefault',
		'radios' => 'extractOptions',
		'checkboxes' => 'extractMultiOptions',
		'default' => 'extractDefault',
	);

	protected $defaultOptions = array(
		'yes' => array('value' => TRUE),
		'no' => array('value' => FALSE),
	);

	public function __construct()
	{}

	public function extract(Question $question)
	{
		$definition = $question->getQuestion();
		$result = $question->getResult();
		$type = $definition['type'];

		if ($result->hasValue())
		{
			$acc = new QuestionAccumulator($this, $result);
			$questionTypeFn = $this->getTypeFunction($this->questionTypeMap, $type);
			$this->{$questionTypeFn}($acc, $question->getSlug(), $definition, $result->getValue());
			return $acc->getMappings();
		}

		return array();
	}

	public function extractSupplements(ScoreMapping $parent, array $definition, Result $result, $index)
	{
		if (isset($definition['supplements']))
		{
			foreach ($definition['supplements'] as $supplement)
			{
				$acc = new SupplementAccumulator($parent);
				$slug = new Slug($supplement['slug'], $parent->getSlug());
				$type = $supplement['type'];
				$supplementTypeFn = $this->getTypeFunction($this->questionTypeMap, $type);
				$this->{$supplementTypeFn}($acc, $slug, $supplement, $result->getSupplement($supplement['slug'], $index));
			}
		}
	}

	private function getTypeFunction($typeMap, $type)
	{
		return isset($typeMap[$type]) ? $typeMap[$type] : $typeMap['default'];
	}

	//////////////////////////////////////
	// question extraction implementations

	protected function extractDefault(IAccumulator $acc, Slug $slug, array $definition, $value)
	{
		$config = isset($definition['score']) ? $definition['score'] : NULL;
		$acc->accumulate(new ScoreMapping($slug, $config, $value), $definition);
	}

	protected function extractOptions(IAccumulator $acc, Slug $slug, array $definition, $value)
	{
		$defaultConfig = isset($definition['score']) ? $definition['score'] : NULL;

		foreach ($definition['options'] as $option)
		{
			if ($option['value'] == $value)
			{
				$acc->accumulate(new ScoreMapping($slug, isset($option['score']) ? $option['score'] : $defaultConfig, $value), $option);
				return;
			}
		}

		// if the selected option does not contain a score configuration, fall back to the question
		// definition's score configuration
		$this->extractDefault($acc, $slug, $definition, $value);
	}

	protected function extractMultiOptions(IAccumulator $acc, Slug $slug, array $definition, $value)
	{
		$defaultConfig = isset($definition['score']) ? $definition['score'] : NULL;
		$options = array();

		foreach ($definition['options'] as $option)
		{
			$options[$option['value']] = $option;
		}

		foreach ($value as $index => $item)
		{
			$val = is_array($item) ? (isset($item['value']) ? $item['value'] : NULL) : $item;
			$option = isset($options[$val]) ? $options[$val] : array();
			$config = isset($option['score']) ? $option['score'] : $defaultConfig;
			$acc->accumulate(new ScoreMapping($slug, $config, $val), $option, $index);
		}
	}

	protected function extractOptionsWithDefault(IAccumulator $acc, Slug $slug, array $definition, $value)
	{
		$options = isset($definition['options']) ? $definition['options'] : array();

		foreach ($this->defaultOptions as $key => $option)
		{
			$options[$key] = isset($options[$key]) ? $options[$key] + $option : $option;
		}

		if (isset($definition['score']))
		{
			$options['yes']['score'] = $definition['score'];
			unset($definition['score']);
		}

		$definition['options'] = $options;

		return $this->extractOptions($acc, $slug, $definition, $value);
	}
}

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreMappingExtractor;

use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreMappingExtractor;
use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreMapping;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Slug;


// Deklariere das Interface 'iTemplate'
interface IAccumulator
{
	public function accumulate(ScoreMapping $mapping, array $scope);
}


/**
 * QuestionAccumulator
 */
class QuestionAccumulator implements IAccumulator
{
	private $extractor;
	private $result;
	private $mappings;

	public function __construct(ScoreMappingExtractor $extractor, Result $result)
	{
		$this->extractor = $extractor;
		$this->result = $result;
		$this->mappings = array();
	}

	public function accumulate(ScoreMapping $mapping, array $scope, $index = -1) {
		$this->extractor->extractSupplements($mapping, $scope, $this->result, $index);
		$this->mappings[] = $mapping;
	}

	public function getMappings()
	{
		return $this->mappings;
	}
}

/**
 * SupplementAccumulator
 */
class SupplementAccumulator implements IAccumulator
{
	private $parent;
	private $mappings;

	public function __construct(ScoreMapping $parent)
	{
		$this->parent = $parent;
	}

	public function accumulate(ScoreMapping $mapping, array $scope) {
		$this->parent->addChild($mapping);
	}
}
