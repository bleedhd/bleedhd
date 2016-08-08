<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


/**
 * Does not change the source value at all.
 */
class Identity extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transformData(ISource $raw)
	{
		return $raw->getValue();
	}
}
