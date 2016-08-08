<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


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
