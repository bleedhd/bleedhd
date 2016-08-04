<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


interface IExtractor
{
	/**
	 * @param AssessmentContext $context the assessment context from which the value should be extracted
	 * @return ISource the extracted value
	 */
	public function extract(AssessmentContext $context);
}
