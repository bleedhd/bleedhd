<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


class ExportService
{
	public function export($fileHandle) {
		$data = [
			['Header1', 'Header2'],
			['alpha', 'beta \' with apostrophe'],
			['one', 'two " with quote'],
			['first', 'second with space'],
		];

		foreach ($data as $line) {
			fputcsv($fileHandle, $line, ',', '"', '\\');
		}
	}
}
