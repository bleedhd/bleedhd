<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\SimpleSource;


class Score extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$result = $context->getAssessment()->getResult();
		$current = isset($result['score']) ? $result['score'] : [];

		$segments = explode('.', $this->reference);
		foreach ($segments as $key) {
			if (!is_array($current) || !isset($current[$key])) {
				return new SimpleSource(NULL);
			}

			$current = $current[$key];
		}

		return new SimpleSource($current);
	}
}
