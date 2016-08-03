<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\MetaResponseValue;


class MetaResponse extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$responses = $context->getResponseMap();

		$resultData = isset($responses[$this->reference]) ? $responses[$this->reference]->getResult() : NULL;

		return new MetaResponseValue(new Result($resultData));
	}
}
