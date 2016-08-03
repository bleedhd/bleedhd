<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;


/**
 * Class ResponseValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the response extractor to facilitate subsequent transforms.
 */
class ResponseValue extends BaseResultValue
{
	/**
	 * @inheritdoc
	 */
	public function hasValue()
	{
		return $this->result->hasValue();
	}

	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		return $this->result->getValue();
	}

	/**
	 * @inheritdoc
	 */
	protected function arrayValueExtract($item)
	{
		return isset($item['value']) ? $item['value'] : '';
	}
}
