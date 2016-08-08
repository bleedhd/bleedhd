<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\SupplementSource;


class Supplement extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$segments = explode('.', $this->reference);
		$supplement = array_pop($segments);
		$questionSlug = implode('.', $segments);

		$question = $context->getQuestion($questionSlug);
		if ($question === NULL) {
			throw new \Exception('Question with slug ' . $this->reference . ' does not seem to exist in assessment of type ' . $context->getAssessment()->getQuestionnaire());
		}

		return new SupplementSource($question, $supplement);
	}
}
