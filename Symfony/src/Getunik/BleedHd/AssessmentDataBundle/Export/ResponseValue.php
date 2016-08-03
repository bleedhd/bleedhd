<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


/**
 * Class ResponseValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the response extractor to facilitate subsequent transforms.
 */
class ResponseValue
{
	/**
	 * @var Result
	 */
	private $result;

	public function __construct(Result $result)
	{
		$this->result = $result;
	}

	public function __toString()
	{
		$value = $this->result->getValue();

		if ($value === NULL) {
			return '';
		}

		if (is_array($value)) {
			return implode(',', array_map(function ($item) {
				return isset($item['value']) ? $item['value'] : '';
			}, $value));
		}

		return (string)$value;
	}

	public function hasValue()
	{
		return $this->result->hasValue();
	}

	public function getResult()
	{
		return $this->result;
	}
}
