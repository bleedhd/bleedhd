<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\BaseResultSource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ResponseSource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\SupplementSource;


abstract class BaseTransform implements ITransform
{
	protected $config;

	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
	 * @param ISource $raw raw value to check
	 * @return BaseResultSource
	 * @throws \Exception if the given raw value does not derive from BaseResultValue
	 */
	protected static function requireResultValue($raw)
	{
		if (!($raw instanceof BaseResultSource)) {
			throw new \Exception(self::class . ' transform requires a result based value but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param ISource $raw raw value to check
	 * @return ResponseSource|SupplementSource
	 * @throws \Exception if the given raw value is neither a ResponseValue nor a SupplementValue
	 */
	protected static function requireActualResultValue(ISource $raw)
	{
		if (!($raw instanceof ResponseSource) && !($raw instanceof SupplementSource)) {
			throw new \Exception(self::class . ' transform requires a ResponseValue or SupplementValue but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param ISource $raw raw value to check
	 * @return ResponseSource
	 * @throws \Exception if the given raw value is not a ResponseValue
	 */
	protected static function requireResponseValue(ISource $raw)
	{
		if (!($raw instanceof ResponseSource)) {
			throw new \Exception(self::class . ' transform requires a ResponseValue but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param BaseResultSource $raw
	 * @return array
	 * @throws \Exception thrown if the result value is not an array
	 */
	protected static function requireMultivalued(BaseResultSource $raw)
	{
		if ($raw->getValue() !== NULL && !is_array($raw->getValue())) {
			throw new \Exception(self::class . ' transform requires a multi-valued result (array), but given ' . $raw->getType());
		}

		return $raw->getValueArray();
	}
}
