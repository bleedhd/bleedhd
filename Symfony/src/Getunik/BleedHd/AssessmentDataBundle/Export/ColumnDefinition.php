<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Extractor\ExtractorFactory;
use Getunik\BleedHd\AssessmentDataBundle\Export\Extractor\IExtractor;
use Getunik\BleedHd\AssessmentDataBundle\Export\Transform\TransformFactory;
use Getunik\BleedHd\AssessmentDataBundle\Export\Transform\ITransform;

class ColumnDefinition
{
	/**
	 * Default transform if none is specified
	 */
	const DEFAULT_TRANSFORM = 'Identity';

	/**
	 * @var array
	 */
	private $spec;

	/**
	 * @var IExtractor
	 */
	private $extractor;

	/**
	 * @var ITransform
	 */
	private $transform;

	public function __construct(array $columnSpec)
	{
		$this->spec = $columnSpec;

		$this->extractor = ExtractorFactory::create($columnSpec['extractor']);
		$this->transform = TransformFactory::create(isset($columnSpec['transform']) ? $columnSpec['transform'] : self::DEFAULT_TRANSFORM);
	}

	public function extract(AssessmentContext $context)
	{
		$raw = $this->extractor->extract($context);
		return $this->transform->transform($raw);
	}

	public function getLabel()
	{
		return isset($this->spec['label']) ? $this->spec['label'] : $this->spec['reference'];
	}
}
