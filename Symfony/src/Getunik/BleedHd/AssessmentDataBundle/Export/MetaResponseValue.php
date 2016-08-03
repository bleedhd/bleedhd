<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


/**
 * Class MetaResponseValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the meta-response extractor to facilitate subsequent transforms.
 */
class MetaResponseValue
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
		$value = $this->result->getMetaValue();

		if ($value === NULL) {
			return '';
		}

		return (string) $value;
	}
}
