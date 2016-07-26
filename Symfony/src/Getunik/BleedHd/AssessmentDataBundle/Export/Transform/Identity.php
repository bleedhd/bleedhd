<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


class Identity implements ITransform
{
	/**
	 * @inheritdoc
	 */
	public function transform($raw)
	{
		return (string) $raw;
	}
}
