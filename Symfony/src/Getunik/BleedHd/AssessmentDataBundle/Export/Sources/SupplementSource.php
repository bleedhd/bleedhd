<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Sources;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


/**
 * Class SupplementValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the supplement extractor to facilitate subsequent transforms.
 */
class SupplementSource extends BaseResultSource
{
	/**
	 * @var string
	 */
	private $slug;

	public function __construct(Question $question, $slug, $option = NULL)
	{
		parent::__construct($question, $option);
		$this->slug = $slug;
	}

	/**
	 * @inheritdoc
	 */
	public function hasValue()
	{
		return $this->getValue() !== NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		if ($this->getResult()->isMultiValue() && $this->option) {
			return $this->getResult()->getSupplementByOption($this->slug, $this->option);
		}

		return $this->getResult()->getSupplement($this->slug);
	}

	/**
	 * @inheritdoc
	 */
	protected function arrayValueExtract($item)
	{
		return $item;
	}

	/**
	 * @return array
	 */
	public function getQuestionConfig()
	{
		$parentConfig = parent::getQuestionConfig();

		if (isset($parentConfig['supplements'])) {
			foreach ($parentConfig['supplements'] as $supplement) {
				if (isset($supplement['slug']) && $supplement['slug'] === $this->slug) {
					return $supplement;
				}
			}
		}

		return [];
	}
}
