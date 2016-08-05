<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Sources;


/**
 * Class ResponseValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the response extractor to facilitate subsequent transforms.
 */
class ResponseSource extends BaseResultSource
{
	/**
	 * @inheritdoc
	 */
	public function hasValue()
	{
		return $this->getResult()->hasValue();
	}

	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		if ($this->getResult()->isMultiValue()) {
			return $this->getValueArray();
		} else {
			return $this->getResult()->getValue();
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function arrayValueExtract($item)
	{
		return isset($item['value']) ? $item['value'] : '';
	}
}
