<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\SimpleSource;


/**
 * Converts individual items of a multi-valued response into tuples by adding one or more supplement values.
 */
class InlineSupplement extends Mapping
{
	private $supplements;
	private $valueSeparator;

	/**
	 * @inheritdoc
	 */
	public function __construct($config)
	{
		parent::__construct($config);

		$this->valueSeparator = isset($this->config['valueSeparator']) ? $this->config['valueSeparator'] : '|';

		$this->supplements = [];
		if (isset($this->config['supplements'])) {
			foreach ($this->config['supplements'] as $supplementConfig) {

				$this->supplements[] = [
					'slug' => $supplementConfig['slug'],
					'transform' => new Mapping($supplementConfig),
				];

			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function transformData(ISource $raw)
	{
		if (!$raw->hasValue()) {
			return '';
		}

		$raw = self::requireResponseValue($raw);

		return $this->extractItems($raw->getResult());
	}

	private function extractItems(Result $result)
	{
		$items = [];

		if ($result->isMultiValue()) {
			$data = $result->getData();
		} else {
			$data = [$result->getData()];
		}

		foreach ($data as $value) {
			$atoms = [parent::transformData(new SimpleSource($value['value']))];

			foreach ($this->supplements as $supplement) {

				$slug = $supplement['slug'];
				/** @var ITransform $transform */
				$transform = $supplement['transform'];
				$supplementValue = isset($value['supplements']) && isset($value['supplements'][$slug]) ? $value['supplements'][$slug] : NULL;
				$source = new SimpleSource($supplementValue);

				$atoms[] = $transform->transform($source);

			}

			$items[] = implode($this->valueSeparator, $atoms);
		}

		return $items;
	}
}
