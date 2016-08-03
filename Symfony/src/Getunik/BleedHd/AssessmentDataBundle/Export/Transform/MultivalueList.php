<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\BaseValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\ResponseValue;
use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\SupplementValue;


class MultivalueList extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(IDataValue $raw)
	{
		if (!$raw->hasValue()) {
			return '';
		}

		if (!($raw instanceof ResponseValue) && !($raw instanceof SupplementValue)) {
			throw new \Exception('ValueOrMeta transform requires a ResponseValue or SupplementValue but received "' . $raw->getType() . '"');
		}

		$emptyList = isset($this->config['emptyValue']) ? $this->config['emptyValue'] : '';
		$separator = isset($this->config['separator']) ? $this->config['separator'] : ',';

		if (!is_array($raw->getValue())) {
			throw new \Exception('Expecting value for MultivalueList transform to be an array, but given ' . $raw->getType());
		}

		$valueArray = $raw->getValueArray();

		if (empty($valueArray)) {
			return $emptyList;
		}

		return implode($separator, $valueArray);
	}
}
