<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Getunik\BleedHd\AssessmentDataBundle\Export\ExportService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;


class ExportController extends FOSRestController
{
	const EXPORT_DOWNLOAD_IDS_SESSION = 'export_download_ids';
	/**
	 * @var ExportService
	 */
	private $exportService;

	public function __construct(ExportService $exportService)
	{
		$this->exportService = $exportService;
	}

	/**
	 * @Security("has_role('ROLE_READER')")
	 * @Post("/export/generate", requirements={"_format"="json|xml"})
	 * @ParamConverter("settings", converter="fos_rest.request_body", class="ArrayCollection")
	 */
	public function exportAction(Request $request, $settings)
	{
		$id = preg_replace('/[+\/=]/', '', base64_encode(random_bytes(16)));
		$path = $this->get('kernel')->getRootDir() . '/../web/export/' . $id;

		ob_start();
		$fp = fopen($path, 'w');
		$this->exportService->export($fp);

		$exportIds = $request->getSession()->get(self::EXPORT_DOWNLOAD_IDS_SESSION);
		if (empty($exportIds)) {
			$exportIds = [];
		}

		$exportIds[] = $id;
		$request->getSession()->set(self::EXPORT_DOWNLOAD_IDS_SESSION, $exportIds);

		return $this->handleView($this->view([
			'msg' => 'exporting',
			'settings' => $settings,
			'id' => $id,
			'name' => 'export-name.csv',
		]));
	}
}
