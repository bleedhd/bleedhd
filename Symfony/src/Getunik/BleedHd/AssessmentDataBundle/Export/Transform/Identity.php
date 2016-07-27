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
			return (string)$raw;
		} catch (\Exception $e) {
			$type = is_object($raw) ? get_class($raw) : gettype($raw);
			return 'unable to display value (' . $type . ')';
		}
	}
}
