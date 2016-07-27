<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Getunik\BleedHd\AssessmentDataBundle\Export\ExportService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;


class ExportController extends FOSRestController
{
	const EXPORT_DOWNLOAD_IDS_SESSION = 'export_download_ids';
	/**
	 * @var ExportService
	 */
	private $exportService;

	/**
	 * @var string
	 */
	private $exportsPath;

	public function __construct(ExportService $exportService, $exportsPath)
	{
		$this->exportService = $exportService;
		$this->exportsPath = $exportsPath;
	}

	/**
	 * @param $request Request the request object
	 * @param $settings array the export settings configuration
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Security("has_role('ROLE_READER')")
	 * @Post("/export/generate", requirements={"_format"="json|xml"})
	 * @ParamConverter("settings", converter="fos_rest.request_body", class="ArrayCollection")
	 */
	public function exportAction(Request $request, $settings)
	{
		// generate a random file name and make sure it only contains "nice" characters
		$id = preg_replace('/[+\/=]/', '', base64_encode(random_bytes(16)));
		$path = $this->exportsPath . '/' . $id;

		$fp = fopen($path, 'w');
		$this->exportService->export($fp);

		// store the generated export ID in the session - this is used by the ExportDownloadController to ensure
		// that only the person who generated the export can download it
		$exportIds = $request->getSession()->get(self::EXPORT_DOWNLOAD_IDS_SESSION);
		if (empty($exportIds)) {
			$exportIds = [];
		}

		$exportIds[] = $id;
		$request->getSession()->set(self::EXPORT_DOWNLOAD_IDS_SESSION, $exportIds);

		return $this->handleView($this->view([
			'status' => 'ok',
			'settings' => $settings,
			'id' => $id,
			'name' => 'export-name.csv',
		]));
	}
}
