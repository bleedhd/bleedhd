<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;


class ValueOrMeta extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(IDataValue $raw)
	{
		$raw = self::requireActualResultValue($raw);

		if ($raw->hasValue()) {
			return $raw->toString();
		} else {
			return $raw->getResult()->getMetaValue();
		}
	}
}
