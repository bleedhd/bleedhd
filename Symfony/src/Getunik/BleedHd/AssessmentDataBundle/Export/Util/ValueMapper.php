<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Util;


class ValueMapper
{
	/**
	 * @var ValueMapPredicate[]
	 */
	private $predicates;

	public function __construct(array $mapConfig)
	{
		$this->predicates = [];

		foreach ($mapConfig as $item) {
			$this->predicates[] = $this->predicateFromConfigItem($item);
		}
	}

	public function map($value)
	{
		foreach ($this->predicates as $predicate) {
			if ($predicate->match($value)) {
				return $predicate->map($value);
			}
		}

		return $value;
	}

	private function predicateFromConfigItem(array $item)
	{
		if (isset($item['default'])) {
			return self::identity($item['default']);
		} else if (isset($item['source'])) {
			return self::simpleMatch($item['source'], $item['value']);
		} else if (isset($item['regex'])) {
			return self::regexMatch($item['regex'], $item['value']);
		}

		throw new \Exception('Cannot create predicate from mapping item ' . json_encode($item));
	}

	private static function identity($value)
	{
		return new ValueMapPredicate(
			function ($valueToTest) {
				return true;
			},
			function ($valueToMap) use ($value) {
				return $value;
			}
		);
	}

	private static function simpleMatch($referenceValue, $mappedValue)
	{
		return new ValueMapPredicate(
			function ($valueToTest) use ($referenceValue) {
				return $referenceValue == $valueToTest;
			},
			function ($valueToMap) use ($mappedValue) {
				return $mappedValue;
			}
		);
	}

	private static function regexMatch($regex, $replacement)
	{
		return new ValueMapPredicate(
			function ($valueToTest) use ($regex) {
				return preg_match($regex, $valueToTest);
			},
			function ($valueToMap) use ($regex, $replacement) {
				return preg_replace($regex, $replacement, $valueToMap);
			}
		);
	}
}
