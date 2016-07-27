<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\SupplementValue;


class Supplement extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$responses = $context->getResponseMap();

		$segments = explode('.', $this->reference);
		$supplement = array_pop($segments);
		$question = implode('.', $segments);

		if (!isset($responses[$question])) {
			return NULL;
		}

		return new SupplementValue(new Result($responses[$question]->getResult()), $supplement);
	}
}
