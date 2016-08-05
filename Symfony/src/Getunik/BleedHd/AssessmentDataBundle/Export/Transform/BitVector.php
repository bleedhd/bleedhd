<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class BitVector extends BaseTransform
{
	private $trueValue;
	private $falseValue;

	public function __construct(array $config)
	{
		parent::__construct($config);

		if (!isset($this->config['listItemSeparator'])) {
			// override default listItemSeparator
			$this->listItemSeparator = '';
		}

		$this->trueValue = isset($config['trueValue']) ? $config['trueValue'] : '1';
		$this->falseValue = isset($config['falseValue']) ? $config['falseValue'] : '0';
	}

	/**
	 * @inheritdoc
	 */
	public function transformData(ISource $raw)
	{
		$raw = self::requireActualResultValue($raw);
		$valueArray = self::requireMultivalued($raw);

		$question = $raw->getQuestionConfig();
		$bits = [];

		foreach ($question['options'] as $option) {
			$bits[] = array_search($option['value'], $valueArray) !== false;
		}

		return array_map([$this, 'bitToString'], $bits);
	}

	/**
	 * @param $bit bool a single bit of the bit vector
	 * @return mixed|string
	 */
	private function bitToString($bit)
	{
		return $bit ? $this->trueValue : $this->falseValue;
	}
}
