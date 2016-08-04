<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\ValueTypes\IDataValue;


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

		$raw = self::requireActualResultValue($raw);

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
