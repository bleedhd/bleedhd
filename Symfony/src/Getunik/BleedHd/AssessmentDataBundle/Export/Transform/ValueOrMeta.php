<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\ResponseValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\SupplementValue;


class ValueOrMeta extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(IDataValue $raw)
	{
		if (!($raw instanceof ResponseValue) && !($raw instanceof SupplementValue)) {
			throw new \Exception('ValueOrMeta transform requires a ResponseValue or SupplementValue but received "' . get_class($raw) . '"');
		}

		if ($raw->hasValue()) {
			return $raw->toString();
		} else {
			return $raw->getResult()->getMetaValue();
		}
	}
}
