<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Result;


/**
 * Class SupplementValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the supplement extractor to facilitate subsequent transforms.
 */
class SupplementValue extends BaseResultValue
{
	/**
	 * @var string
	 */
	private $slug;

	public function __construct(Result $result, $slug)
	{
		parent::__construct($result);
		$this->slug = $slug;
	}

	/**
	 * @inheritdoc
	 */
	public function hasValue()
	{
		return $this->getValue() !== NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		return $this->result->getSupplement($this->slug);
	}

	/**
	 * @inheritdoc
	 */
	protected function arrayValueExtract($item)
	{
		return $item;
	}
}
