<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


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

	/**
	 * @param $item mixed
	 * @return mixed
	 */
	protected function arrayValueExtract($item)
	{
		return $item;
	}

	/**
	 * @return array|null
	 */
	public function getValueArray()
	{
		if ($this->getValue() === NULL) {
			return NULL;
		}

		return array_map([$this, 'arrayValueExtract'], $this->getValue());
	}

	/**
	 * @return Question
	 */
	public function getQuestion()
	{
		return $this->question;
	}

	/**
	 * @return array
	 */
	public function getQuestionConfig()
	{
		return $this->question->getQuestion();
	}

	/**
	 * @return Result
	 */
	public function getResult()
	{
		return $this->question->getResult();
	}
}
