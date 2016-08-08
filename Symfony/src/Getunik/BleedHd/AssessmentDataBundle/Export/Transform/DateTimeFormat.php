<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


/**
 * Formats the source using PHP @see DateTime::format()
 */
class DateTimeFormat extends BaseTransform
{
	/**
	 * Default format string for DateTime objects
	 */
	const DEFAULT_FORMAT = 'Y-m-d H:i:s';

	/**
	 * @inheritdoc
	 */
	public function transformData(ISource $raw)
	{
		$value = $raw->getValue();
		if (!($value instanceof \DateTime)) {
			throw new \Exception('DateTimeFormat transform can only be used on DateTime objects, but given ' . $raw->getType());
		}

		return $value->format(isset($this->config['format']) ? $this->config['format'] : self::DEFAULT_FORMAT);
	}
}
