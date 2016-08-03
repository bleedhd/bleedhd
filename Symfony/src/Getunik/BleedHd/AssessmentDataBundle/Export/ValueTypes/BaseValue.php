<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;


abstract class BaseValue implements IDataValue
{
	/**
	 * @inheritdoc
	 */
	public function hasValue()
	{
		return !empty($this->getValue());
	}

	/**
	 * @inheritdoc
	 */
	public function toString()
	{
		if ($this->getValue() === NULL) {
			return '';
		}

		try {
			return (string) $this->getValue();
		} catch (\Exception $e) {
			return 'unable to display value (' . self::getType($this->getValue()) . ')';
		}
	}

	public function __toString()
	{
		return $this->toString();
	}

	public static function getType($value)
	{
		return is_object($value) ? get_class($value) : gettype($value);
	}
}
