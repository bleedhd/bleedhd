<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\BaseResultSource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ResponseSource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\SupplementSource;


abstract class BaseTransform implements ITransform
{
	protected $config;

	protected $inlineMeta;
	protected $listItemSeparator;
	protected $listEmptyValue;
	protected $prefix;
	protected $suffix;

	public function __construct($config)
	{
		$this->config = $config;

		$this->inlineMeta = isset($this->config['inlineMeta']) ? $this->config['inlineMeta'] : false;
		$this->listItemSeparator = isset($this->config['listItemSeparator']) ? $this->config['listItemSeparator'] : ',';
		$this->listEmptyValue = isset($this->config['listEmptyValue']) ? $this->config['listEmptyValue'] : '';
		$this->prefix = isset($this->config['prefix']) ? $this->config['prefix'] : '';
		$this->suffix = isset($this->config['suffix']) ? $this->config['suffix'] : '';
	}

	public function transform(ISource $raw)
	{
		if (!$raw->hasValue()) {
			if ($this->inlineMeta && $result = self::isActualResultSource($raw)) {
				return $result->getResult()->getMetaValue();
			} else {
				return '';
			}
		}

		$transformed = $this->transformData($raw);

		if (is_array($transformed)) {
			if (empty($transformed)) {
				return $this->listEmptyValue;
			}

			return $this->prefix . implode($this->listItemSeparator, array_map([self::class, 'defaultToString'], $transformed)) . $this->suffix;
		}

		return $this->prefix . self::defaultToString($transformed) . $this->suffix;
	}

	public abstract function transformData(ISource $raw);

	protected static function defaultToString($value)
	{
		if (is_bool($value)) {
			return $value ? 'true' : 'false';
		}

		return (string)$value;
	}

	/**
	 * @param ISource $raw raw value to check
	 * @return BaseResultSource
	 * @throws \Exception if the given raw value does not derive from BaseResultValue
	 */
	protected static function requireResultValue($raw)
	{
		if (!($raw instanceof BaseResultSource)) {
			throw new \Exception(self::class . ' transform requires a result based value but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param ISource $raw raw value to check
	 * @return ResponseSource|SupplementSource
	 * @throws \Exception if the given raw value is neither a ResponseValue nor a SupplementValue
	 */
	protected static function requireActualResultValue(ISource $raw)
	{
		if (!($raw instanceof ResponseSource) && !($raw instanceof SupplementSource)) {
			throw new \Exception(self::class . ' transform requires a ResponseValue or SupplementValue but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param ISource $raw
	 * @return BaseResultSource|null
	 */
	protected static function isActualResultSource(ISource $raw)
	{
		return (($raw instanceof ResponseSource) || ($raw instanceof SupplementSource)) ? $raw : NULL;
	}

	/**
	 * @param ISource $raw raw value to check
	 * @return ResponseSource
	 * @throws \Exception if the given raw value is not a ResponseValue
	 */
	protected static function requireResponseValue(ISource $raw)
	{
		if (!($raw instanceof ResponseSource)) {
			throw new \Exception(self::class . ' transform requires a ResponseValue but received "' . $raw->getType() . '"');
		}

		return $raw;
	}

	/**
	 * @param BaseResultSource $raw
	 * @return array
	 * @throws \Exception thrown if the result value is not an array
	 */
	protected static function requireMultivalued(BaseResultSource $raw)
	{
		if ($raw->getValue() !== NULL && !is_array($raw->getValue())) {
			throw new \Exception(self::class . ' transform requires a multi-valued result (array), but given ' . $raw->getType());
		}

		return $raw->getValueArray();
	}
}
