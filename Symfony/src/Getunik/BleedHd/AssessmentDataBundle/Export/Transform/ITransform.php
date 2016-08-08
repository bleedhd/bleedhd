<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


/**
 * Interface for all export transformations
 */
interface ITransform
{
	/**
	 * @param $raw ISource raw unprocessed value
	 * @return string transformed, CSV-compatible string value
	 */
	public function transform(ISource $raw);
}
