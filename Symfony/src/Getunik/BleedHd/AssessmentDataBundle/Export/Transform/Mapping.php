<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class Mapping extends BaseTransform
{
	/**
	 * @var array
	 */
	private $map = [];

	public function __construct(array $config)
	{
		parent::__construct($config);

		foreach ($this->config['map'] as $item) {
			$this->map[] = $this->createPredicate($item);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function transform(ISource $raw)
	{
		if (!$raw->hasValue()) {
			return '';
		}

		$source = $raw->getValue();

		foreach ($this->map as $matcher) {
			if ($res = $matcher['predicate']($source)) {
				return is_callable($matcher['value']) ? $matcher['value']($res) : $matcher['value'];
			}
		}

		return $source;
	}

	private function createPredicate(array $item)
	{
		if (isset($item['default'])) {
			return [
				'predicate' => [self::class, 'predicateTrue'],
				'value' => $item['default'],
			];
		} else if (isset($item['source'])) {
			return [
				'predicate' => self::simpleMatch($item['source']),
				'value' => $item['value'],
			];
		} else if (isset($item['regex'])) {
			return [
				'predicate' => self::regexMatch($item['regex'], $item['value']),
				'value' => function ($res) { return $res; },
			];
		}

		throw new \Exception('Cannot create predicate from mapping item ' . json_encode($item));
	}

	private static function predicateTrue($valueToTest)
	{
		return true;
	}

	private static function simpleMatch($referenceValue)
	{
		return function ($valueToTest) use ($referenceValue) {
			return $referenceValue == $valueToTest;
		};
	}

	private static function regexMatch($regex, $replacement)
	{
		return function ($valueToTest) use ($regex, $replacement) {
			if (preg_match($regex, $valueToTest)) {
				return preg_replace($regex, $replacement, $valueToTest);
			}

			return false;
		};
	}
}
