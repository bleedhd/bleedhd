<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\SupplementValue;


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

		return new SupplementValue($question, $supplement);
	}
}
