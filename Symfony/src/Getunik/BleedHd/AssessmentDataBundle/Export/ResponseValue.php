<?php


namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;

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

		return is_array($value) ? json_encode($value) : (string) $value;
	}
}
