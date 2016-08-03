<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


abstract class BaseResultValue extends BaseValue
{
	/**
	 * @var Result
	 */
	protected $result;

	public function __construct(Result $result)
	{
		$this->result = $result;
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
			return implode(',', array_map([$this, 'arrayValueExtract'], $value));
		}

		return (string)$value;
	}

	protected function arrayValueExtract($item)
	{
		return $item;
	}

	public function getResult()
	{
		return $this->result;
	}
}
