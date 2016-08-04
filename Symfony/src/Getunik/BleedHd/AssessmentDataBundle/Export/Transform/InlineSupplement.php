<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class InlineSupplement extends BaseTransform
{
	private $supplements;
	private $valueSeparator;

	public function __construct($config)
	{
		parent::__construct($config);

		$this->supplements = isset($this->config['supplements']) ? $this->config['supplements'] : [];
		$this->valueSeparator = isset($this->config['valueSeparator']) ? $this->config['valueSeparator'] : '|';
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
			$atoms = [];

			$atoms[] = $value['value'];
			foreach ($this->supplements as $supplement) {
				$slug = $supplement['slug'];
				$atoms[] = isset($value['supplements']) && isset($value['supplements'][$slug]) ? $value['supplements'][$slug] : NULL;
			}

			$items[] = implode($this->valueSeparator, $atoms);
		}

		return $items;
	}
}
