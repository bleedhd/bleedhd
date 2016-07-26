<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;

interface IExtractor
{
	/**
	 * @param AssessmentContext $context the assessment context from which the value should be extracted
	 * @return mixed the extracted value
	 */
	public function extract(AssessmentContext $context);
}
