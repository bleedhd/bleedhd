<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class BitVector extends BaseTransform
{
	private $trueValue;
	private $falseValue;
	private $nullValue;
	private $separator;

	public function __construct(array $config)
	{
		parent::__construct($config);

		$this->trueValue = isset($config['trueValue']) ? $config['trueValue'] : '1';
		$this->falseValue = isset($config['falseValue']) ? $config['falseValue'] : '0';
		$this->nullValue = isset($config['nullValue']) ? $config['nullValue'] : '';
		$this->separator = isset($config['separator']) ? $config['separator'] : '';
	}

	/**
	 * @inheritdoc
	 */
	public function transform(ISource $raw)
	{
		$raw = self::requireActualResultValue($raw);
		$valueArray = self::requireMultivalued($raw);

		if ($valueArray === NULL) {
			return $this->nullValue;
		}

		$question = $raw->getQuestionConfig();
		$bits = [];

		foreach ($question['options'] as $option) {
			$bits[] = array_search($option['value'], $valueArray) !== false;
		}

		$result = implode($this->separator, array_map([$this, 'bitToString'], $bits));

		return $result;
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
