<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class Identity extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(ISource $raw)
	{
		return $raw->toString();
	}
}
