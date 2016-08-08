<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;


abstract class BaseExtractor implements IExtractor
{
	protected $reference;

	public function __construct($reference)
	{
		$this->reference = $reference;
	}
}
