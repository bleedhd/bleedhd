<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;


/**
 * ExportResult
 *
 * For the export, an empty array has to be handled differently than for the rest of the application since an empty
 * array may represent a very specific value - the one where no option was selected explicitly.
 */
class ExportResult extends Result
{
	public function hasValue()
	{
		return isset($this->result['data']) && (is_array($this->result['data']) || !empty($this->result['data']));
	}
}
