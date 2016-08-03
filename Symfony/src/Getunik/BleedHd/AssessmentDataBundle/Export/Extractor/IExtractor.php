<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;


interface IExtractor
{
	/**
	 * @param AssessmentContext $context the assessment context from which the value should be extracted
	 * @return IDataValue the extracted value
	 */
	public function extract(AssessmentContext $context);
}
