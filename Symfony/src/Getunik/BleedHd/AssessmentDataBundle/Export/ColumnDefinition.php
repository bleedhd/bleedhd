<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Export\Extractor\ExtractorFactory;
use Getunik\BleedHd\AssessmentDataBundle\Export\Extractor\IExtractor;
use Getunik\BleedHd\AssessmentDataBundle\Export\Transform\TransformFactory;
use Getunik\BleedHd\AssessmentDataBundle\Export\Transform\ITransform;


/**
 * Class ColumnDefinition
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Represents a column in a CSV export. It is created from the export column configuration and extracts the relevant
 * value for this column from the given assessment context.
 */
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

		$this->extractor = ExtractorFactory::create($columnSpec['extractor'], $columnSpec['reference']);

		if (isset($columnSpec['transform'])) {
			$this->transform = TransformFactory::create($columnSpec['transform']['type'], $columnSpec['transform']);
		} else {
			$this->transform = TransformFactory::create(self::DEFAULT_TRANSFORM, []);
		}
	}

	/**
	 * Extracts and transforms a value from the assessment context according to the column configuration.
	 *
	 * @param AssessmentContext $context the assessment context from which a value should be extracted
	 * @return string
	 */
	public function extract(AssessmentContext $context)
	{
		try {
			$raw = $this->extractor->extract($context);
			$result = $this->transform->transform($raw);
		} catch (\Exception $e) {
			$result = 'ERROR: ' . $e->getMessage();
		}

		return $result;
	}

	/**
	 * Returns the label of the column.
	 * @return string
	 */
	public function getLabel()
	{
		return isset($this->spec['label']) ? $this->spec['label'] : $this->spec['reference'];
	}
}
