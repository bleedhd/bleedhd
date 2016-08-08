<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ResponseSource;


class Response extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$segments = explode('.', $this->reference);

		$option = preg_match('/^@/', end($segments)) ? substr(array_pop($segments), 1) : NULL;
		$slug = implode('.', $segments);

		$question = $context->getQuestion($slug);
		if ($question === NULL) {
			throw new \Exception('Question with slug ' . $slug . ' does not seem to exist in assessment of type ' . $context->getAssessment()->getQuestionnaire());
		}

		return new ResponseSource($question, $option);
	}
}
