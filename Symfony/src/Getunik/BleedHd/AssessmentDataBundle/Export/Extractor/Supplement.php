<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\SupplementValue;


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

		$resultData = isset($responses[$question]) ? $responses[$question]->getResult() : NULL;

		return new SupplementValue(new Result($resultData), $supplement);
	}
}
