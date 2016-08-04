<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\MetaResponseValue;


class MetaResponse extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$question = $context->getQuestion($this->reference);

		return new MetaResponseValue($question);
	}
}
