<?php


namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;

class SupplementValue
{
	/**
	 * @var array
	 */
	private $supplement;

	public function __construct(Result $result, $slug)
	{
		$this->supplement = $result->getSupplement($slug);
	}

	public function __toString()
	{
		if ($this->supplement === NULL) {
			return '';
		}

		if (is_array($this->supplement)) {
			return implode(',', $this->supplement);
		}

		return (string) $this->supplement;
	}
}
