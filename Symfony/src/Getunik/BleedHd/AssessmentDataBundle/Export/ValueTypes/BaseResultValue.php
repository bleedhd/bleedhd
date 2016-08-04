<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;


abstract class BaseResultValue extends BaseValue
{
	/**
	 * @var Question
	 */
	private $question;

	public function __construct(Question $question)
	{
		$this->question = $question;
	}

	/**
	 * @inheritdoc
	 */
	public function toString()
	{
		$value = $this->getValue();

		if ($value === NULL) {
			return '';
		}

		if (is_array($value)) {
			return implode(',', $this->getValueArray());
		}

		return (string)$value;
	}

	protected function arrayValueExtract($item)
	{
		return $item;
	}

	public function getValueArray()
	{
		return array_map([$this, 'arrayValueExtract'], $this->getValue());
	}

	public function getQuestion()
	{
		return $this->question;
	}

	public function getResult()
	{
		return $this->question->getResult();
	}
}
