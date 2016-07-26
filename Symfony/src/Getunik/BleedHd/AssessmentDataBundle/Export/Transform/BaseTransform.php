<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


abstract class BaseTransform implements ITransform
{
	protected $config;

	public function __construct($config)
	{
		$this->config = $config;
	}
}
