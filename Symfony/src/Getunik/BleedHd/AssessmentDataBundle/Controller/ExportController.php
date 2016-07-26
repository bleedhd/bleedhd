<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Getunik\BleedHd\AssessmentDataBundle\Export\ExportService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ExportController extends FOSRestController
{
	public function __construct(ExportService $exportService)
	{
		$this->exportService = $exportService;
	}

	/**
	 * @Security("has_role('ROLE_READER')")
	 * @Post("/export/generate", requirements={"_format"="json|xml"})
	 * @ParamConverter("settings", converter="fos_rest.request_body", class="ArrayCollection")
	 */
	public function exportAction($settings)
	{
		ob_start();
		$fp = fopen('php://output', 'w');
		$this->exportService->export($fp);
		$csv = ob_get_clean();

		return $this->handleView($this->view([
			'msg' => 'exporting',
			'settings' => $settings,
			'data' => $csv,
		]));
	}
}
