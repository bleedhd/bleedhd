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

		return is_array($this->supplement) ? json_encode($this->supplement) : (string) $this->supplement;
	}
}
