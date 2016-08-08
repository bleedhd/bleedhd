<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Annotation;


/**
 * Class FeatureRequire
 * @package Getunik\BleedHd\AssessmentDataBundle\Annotation
 *
 * @Annotation
 */
class FeatureRequire
{
	private $feature;

	public function __construct($options)
	{
		if (isset($options['value'])) {
			$this->feature = $options['value'];
		} else {
			throw new \Exception('Missing value for FeatureRequire annotation');
		}
	}

	public function getFeature()
	{
		return $this->feature;
	}
}
