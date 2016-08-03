<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


/**
 * Class SupplementValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the supplement extractor to facilitate subsequent transforms.
 */
class SupplementValue
{
	/**
	 * @var Result
	 */
	private $result;

	/**
	 * @var string
	 */
	private $slug;

	public function __construct(Result $result, $slug)
	{
		$this->result = $result;
		$this->slug = $slug;
	}

	public function __toString()
	{
		$value = $this->result->getSupplement($this->slug);

		if ($value === NULL) {
			return '';
		}

		if (is_array($value)) {
			return implode(',', $value);
		}

		return (string)$value;
	}

	public function hasValue()
	{
		return !empty($this->result->getSupplement($this->slug));
	}

	public function getResult()
	{
		return $this->result;
	}
}
