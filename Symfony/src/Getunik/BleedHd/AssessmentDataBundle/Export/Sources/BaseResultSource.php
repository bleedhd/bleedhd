<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Sources;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


abstract class BaseResultSource extends BaseSource
{
	/**
	 * @var Question
	 */
	private $question;

	/**
	 * @var string|null
	 */
	protected $option;

	public function __construct(Question $question, $option = NULL)
	{
		$this->question = $question;
		$this->option = $option;
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
		$value = $this->getResult()->getValue();
		if ($value === NULL) {
			return NULL;
		}

		return array_map([$this, 'arrayValueExtract'], $value);
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
