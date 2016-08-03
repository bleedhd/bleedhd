<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\ResponseValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\SupplementValue;


class ValueOrMeta extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform($raw)
	{
		if (!($raw instanceof ResponseValue) && !($raw instanceof SupplementValue)) {
			throw new \Exception('ValueOrMeta transform requires a ResponseValue or SupplementValue but received "' . (is_object($raw) ? get_class($raw) : gettype($raw)) . '"');
		}

		/** @var $result Result */
		$result = $raw->getResult();

		if ($raw->hasValue()) {
			return (string)$raw;
		} else {
			return $raw->getResult()->getMetaValue();
		}
	}
}
