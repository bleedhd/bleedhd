<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Sources;


interface ISource
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
	 * @return string gets a string representation describing the type of value that is wrapped
	 */
	public function getType();

	/**
	 * @return string returns a string representation of the wrapped value
	 */
	public function toString();
}
