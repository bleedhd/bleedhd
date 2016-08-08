<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\SimpleSource;


class PatientField extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$patient = $context->getAssessment()->getPatient();

		$getter = 'get' . ucfirst($this->reference);
		if (!method_exists($patient, $getter)) {
			throw new \Exception('Patient entity does not have a getter called "' . $getter . '"');
		}

		return new SimpleSource($patient->{$getter}());
	}
}
