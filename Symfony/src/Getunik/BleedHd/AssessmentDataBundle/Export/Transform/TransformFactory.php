<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


class TransformFactory
{
	/**
	 * @param $transformType string - transform type name
	 * @return ITransform the transform instance from the given type name
	 * @throws \Exception if the requested transform type does not exist
	 */
	public static function create($transformType, $transformConfig)
	{
		$className = 'Getunik\BleedHd\AssessmentDataBundle\Export\Transform\\' . $transformType;

		if (!class_exists($className)) {
			throw new \Exception('Unknown transform type "' . $transformType . '"');
		}

		return new $className($transformConfig);
	}
}
