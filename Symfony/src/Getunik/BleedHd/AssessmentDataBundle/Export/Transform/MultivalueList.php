<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Transform;

use Getunik\BleedHd\AssessmentDataBundle\Export\Sources\ISource;


class MultivalueList extends BaseTransform
{
	/**
	 * @inheritdoc
	 */
	public function transform(ISource $raw)
	{
		if (!$raw->hasValue()) {
			return '';
		}

		$raw = self::requireActualResultValue($raw);

		$emptyList = isset($this->config['emptyValue']) ? $this->config['emptyValue'] : '';
		$separator = isset($this->config['separator']) ? $this->config['separator'] : ',';

		$valueArray = self::requireMultivalued($raw);

		if (empty($valueArray)) {
			return $emptyList;
		}

		return implode($separator, $valueArray);
	}
}
