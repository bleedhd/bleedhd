<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Sources;


class SimpleSource extends BaseSource
{
	/**
	 * @var mixed
	 */
	private $value;

	/**
	 * SimpleValue constructor.
	 * @param $value mixed the inner value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		return $this->value;
	}
}