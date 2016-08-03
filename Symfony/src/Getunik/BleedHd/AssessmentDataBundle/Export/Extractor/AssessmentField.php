<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\SimpleValue;


class AssessmentField extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$assessment = $context->getAssessment();

		$getter = 'get' . ucfirst($this->reference);
		if (!method_exists($assessment, $getter)) {
			throw new \Exception('Assessment entity does not have a getter called "' . $getter . '"');
		}

		return new SimpleValue($assessment->{$getter}());
	}
}
