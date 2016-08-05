<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Util;


class ValueMapPredicate
{
	/**
	 * @var callable
	 */
	private $predicate;
	/**
	 * @var callable
	 */
	private $mapper;

	public function __construct($predicate, $mapper)
	{
		$this->predicate = $predicate;
		$this->mapper = $mapper;
	}

	public function match($value)
	{
		return call_user_func($this->predicate, $value);
	}

	public function map($value)
	{
		return call_user_func($this->mapper, $value);
	}
}
