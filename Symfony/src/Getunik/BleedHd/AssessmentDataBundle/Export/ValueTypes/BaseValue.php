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
	public function getType()
	{
		$value = $this->getValue();
		return is_object($value) ? get_class($value) : gettype($value);
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
			return 'unable to display value (' . $this->getType() . ')';
		}
	}

	public function __toString()
	{
		return $this->toString();
	}
}
