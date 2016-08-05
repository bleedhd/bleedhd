<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\MetaResponseSource;


class MetaResponse extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$question = $context->getQuestion($this->reference);
		if ($question === NULL) {
			throw new \Exception('Question with slug ' . $this->reference . ' does not seem to exist in assessment of type ' . $context->getAssessment()->getQuestionnaire());
		}

		return new MetaResponseSource($question);
	}
}
