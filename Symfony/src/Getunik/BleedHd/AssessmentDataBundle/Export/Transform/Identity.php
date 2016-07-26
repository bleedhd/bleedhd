<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


class Identity extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform($raw)
	{
		try {
			return (string) $raw;
		} catch (\Exception $e) {
			return 'unable to display value (' . get_class($raw) . ')';
		}
	}
}
