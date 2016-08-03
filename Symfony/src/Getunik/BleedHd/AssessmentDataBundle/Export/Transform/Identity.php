<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;


class Identity extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(IDataValue $raw)
	{
		return $raw->toString();
	}
}
