<?php
/**
 * Created by PhpStorm.
 * User: lukas
 * Date: 26.07.16
 * Time: 13:54
 */

namespace Getunik\BleedHd\AssessmentDataBundle\Export\Extractor;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;

class AssessmentField extends BaseExtractor
{
	/**
	 * @inheritdoc
	 */
	public function extract(AssessmentContext $context)
	{
		$assessment = $context->getAssessment();

		$getter = 'get' . ucfirst($this->reference);
		if (!method_exists($assessment, $getter)) {
			throw new \Exception('Assessment entity does not have a getter called "' . $getter . '"');
		}

		return $assessment->{$getter}();
	}
}
