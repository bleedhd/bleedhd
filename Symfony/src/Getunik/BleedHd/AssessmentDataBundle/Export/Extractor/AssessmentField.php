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
		return 'unknown';
	}
}
