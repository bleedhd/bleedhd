<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\BaseResultValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\ResponseValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\SupplementValue;


abstract class BaseTransform implements ITransform
{
	protected $config;

	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
	 * @param $raw IDataValue raw value to check
	 * @return BaseResultValue
	 * @throws \Exception if the given raw value does not derive from BaseResultValue
	 */
	protected static function requireResultValue($raw)
	{
		if (!($raw instanceof BaseResultValue)) {
			throw new \Exception(self::class . ' transform requires a result based value but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param $raw IDataValue raw value to check
	 * @return ResponseValue|SupplementValue
	 * @throws \Exception if the given raw value is neither a ResponseValue nor a SupplementValue
	 */
	protected static function requireActualResultValue(IDataValue $raw)
	{
		if (!($raw instanceof ResponseValue) && !($raw instanceof SupplementValue)) {
			throw new \Exception(self::class . ' transform requires a ResponseValue or SupplementValue but received "' . $raw->getType() . '"');
		}

		return $raw;
	}
}
