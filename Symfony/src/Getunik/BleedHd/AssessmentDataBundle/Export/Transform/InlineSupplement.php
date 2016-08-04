<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class InlineSupplement extends BaseTransform
{
	private $supplements;
	private $emptyValue;
	private $itemSeparator;
	private $valueSeparator;

	public function __construct($config)
	{
		parent::__construct($config);

		$this->supplements = isset($this->config['supplement']) ? explode(',', $this->config['supplement']) : [];
		$this->emptyValue = isset($this->config['emptyValue']) ? $this->config['emptyValue'] : '';
		$this->itemSeparator = isset($this->config['itemSeparator']) ? $this->config['itemSeparator'] : ',';
		$this->valueSeparator = isset($this->config['valueSeparator']) ? $this->config['valueSeparator'] : '|';
	}

	/**
	 * @inheritdoc
	 */
	public function transform(ISource $raw)
	{
		if (!$raw->hasValue()) {
			return '';
		}

		$raw = self::requireResponseValue($raw);
		$items = $this->extractItems($raw->getResult());

		if (empty($items)) {
			return $this->emptyValue;
		}

		return implode($this->itemSeparator, $items);
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
			$atoms = [];

			$atoms[] = $value['value'];
			foreach ($this->supplements as $slug) {
				$atoms[] = isset($value['supplements']) && isset($value['supplements'][$slug]) ? $value['supplements'][$slug] : NULL;
			}

			$items[] = implode($this->valueSeparator, $atoms);
		}

		return $items;
	}
}
