<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\ResponseValue;


class Response extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$responses = $context->getResponseMap();

		if (!isset($responses[$this->reference])) {
			return NULL;
		}

		return new ResponseValue(new Result($responses[$this->reference]->getResult()));
	}
}
