<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


class DateTimeFormat extends BaseTransform
{
	/**
	 * Default format string for DateTime objects
	 */
	const DEFAULT_FORMAT = 'Y-m-d H:i:s';

	/**
	 * @inheritdoc
	 */
	public function transform($raw)
	{
		if ($raw !== NULL && !($raw instanceof \DateTime)) {
			throw new \Exception('DateTimeFormat transform can only be used on DateTime objects, but given ' . get_class($raw));
		}

		return $raw->format(isset($this->config['format']) ? $this->config['format'] : self::DEFAULT_FORMAT);
	}
}
