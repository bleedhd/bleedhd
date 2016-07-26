<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


interface ITransform
{
	/**
	 * @param $raw mixed raw unprocessed value
	 * @return string transformed, CSV-compatible string value
	 */
	public function transform($raw);
}
