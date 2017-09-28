<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Getunik\BleedHd\AssessmentDataBundle\Annotation\FeatureRequire;
use Getunik\BleedHd\AssessmentDataBundle\Export\ExportService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class ExportController
 * @package Getunik\BleedHd\AssessmentDataBundle\Controller
 *
 * @FeatureRequire("export")
 */
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
	 * @param $request Request the request object
	 * @param $settings array the export settings configuration; see @see ExportService::export($settings)
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Security("has_role('ROLE_READER')")
	 * @Post("/export/generate", requirements={"_format"="json|xml"})
	 * @ParamConverter("settings", converter="fos_rest.request_body", class="ArrayCollection")
	 */
	public function exportAction(Request $request, $settings)
	{
		$export = $this->exportService->export($settings);

		// store the generated export ID in the session - this is used by the ExportDownloadController to ensure
		// that only the person who generated the export can download it
		$exportIds = $request->getSession()->get(self::EXPORT_DOWNLOAD_IDS_SESSION);
		if (empty($exportIds)) {
			$exportIds = [];
		}

		$exportIds[] = $export['id'];
		$request->getSession()->set(self::EXPORT_DOWNLOAD_IDS_SESSION, $exportIds);

		return $this->handleView($this->view(array_merge([
			'status' => 'ok',
			'settings' => $settings,
			], $export
		)));
	}

	/**
	 * @param $settings array the export settings configuration; see @see ExportService::export($settings)
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Security("has_role('ROLE_READER')")
	 * @Post("/export/count", requirements={"_format"="json|xml"})
	 * @ParamConverter("settings", converter="fos_rest.request_body", class="ArrayCollection")
	 */
	public function countAction($settings)
	{
		$count = $this->exportService->count($settings);

		return $this->handleView($this->view([
			'status' => 'ok',
			'count' => $count,
		]));
	}

	/**
	 * @Security("has_role('ROLE_READER')")
	 * @Get("/exportconfigs")
	 */
	public function getConfigurationsAction()
	{
		return $this->handleView($this->view($this->exportService->getConfigurationMap()));
	}
}