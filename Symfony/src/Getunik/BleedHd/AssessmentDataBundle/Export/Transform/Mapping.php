<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;


use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;
use Getunik\BleedHd\AssessmentDataBundle\Export\Util\ValueMapper;


/**
 * Transforms source value using a @see ValueMapper constructed from the transform configuration.
 */
class Mapping extends BaseTransform
{
	/**
	 * @var ValueMapper
	 */
	private $mapper;

	/**
	 * @inheritdoc
	 */
	public function __construct(array $config)
	{
		parent::__construct($config);

		$this->mapper = new ValueMapper(isset($this->config['map']) ? $this->config['map'] : []);
	}

	/**
	 * @inheritdoc
	 */
	public function transformData(ISource $raw)
	{
		return $this->mapper->map($raw->getValue());
	}
}
