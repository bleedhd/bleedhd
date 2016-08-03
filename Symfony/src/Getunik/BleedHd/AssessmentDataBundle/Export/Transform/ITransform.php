<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;


interface ITransform
{
	/**
	 * @param $raw IDataValue raw unprocessed value
	 * @return string transformed, CSV-compatible string value
	 */
	public function transform(IDataValue $raw);
}
