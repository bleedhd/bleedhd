<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;


interface IDataValue
{
	/**
	 * @return bool flag indicating whether this represents a NULL-type value
	 */
	public function hasValue();

	/**
	 * @return mixed gets the underlying value
	 */
	public function getValue();

	/**
	 * @return string returns a string representation of the wrapped value
	 */
	public function toString();
}
