<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


class Identity implements ITransform
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
