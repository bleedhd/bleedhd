<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


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
	public function transform($raw)
	{
		$source = (string)$raw;

		foreach ($this->map as $matcher) {
			if ($matcher['predicate']($source)) {
				return $matcher['value'];
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
		}

		if (isset($item['source'])) {
			return [
				'predicate' => self::simpleMatch($item['source']),
				'value' => $item['value'],
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
}
