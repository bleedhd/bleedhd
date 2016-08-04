<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class ValueOrMeta extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(ISource $raw)
	{
		$raw = self::requireActualResultValue($raw);

		if ($raw->hasValue()) {
			return $raw->toString();
		} else {
			return $raw->getResult()->getMetaValue();
		}
	}
}
