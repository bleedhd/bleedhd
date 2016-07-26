<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;


class ExtractorFactory
{
	/**
	 * @param $extractorType string - extractor type name
	 * @return IExtractor the extractor instance from the given type name
	 * @throws \Exception if the requested extractor type does not exist
	 */
	public static function create($extractorType, $reference)
	{
		$className = 'Getunik\BleedHd\AssessmentDataBundle\Export\Extractor\\' . $extractorType;

		if (!class_exists($className)) {
			throw new \Exception('Unknown extractor type "' . $extractorType . '"');
		}

		return new $className($reference);
	}
}
