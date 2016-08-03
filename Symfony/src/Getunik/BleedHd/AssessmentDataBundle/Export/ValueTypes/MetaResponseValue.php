<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes;


/**
 * Class MetaResponseValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the meta-response extractor to facilitate subsequent transforms.
 */
class MetaResponseValue extends BaseResultValue
{
	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		return $this->result->getMetaValue();
	}
}
