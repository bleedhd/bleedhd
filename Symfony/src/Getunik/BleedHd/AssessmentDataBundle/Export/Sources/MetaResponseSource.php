<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Sources;


/**
 * Class MetaResponseValue
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 *
 * Wrapper type that is returned by the meta-response extractor to facilitate subsequent transforms.
 */
class MetaResponseSource extends BaseResultSource
{
	/**
	 * @inheritdoc
	 */
	public function getValue()
	{
		return $this->getResult()->getMetaValue();
	}
}
