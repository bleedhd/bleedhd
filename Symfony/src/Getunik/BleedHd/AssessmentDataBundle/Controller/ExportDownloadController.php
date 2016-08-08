<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportDownloadController extends Controller
{
	public function downloadExportAction(Request $request, $id, $name)
	{
		$path = $this->get('kernel')->getRootDir() . '/../var/export/' . $id;
		$sessionIds = $request->getSession()->get(ExportController::EXPORT_DOWNLOAD_IDS_SESSION);

		if (empty($sessionIds) || array_search($id, $sessionIds) === false) {
			throw $this->createNotFoundException('The export file was not found');
		} else {
			$fileContent = file_get_contents($path);
			$response = new Response($fileContent);

			$disposition = $response->headers->makeDisposition(
				ResponseHeaderBag::DISPOSITION_ATTACHMENT,
				$name
			);

			$response->headers->set('Content-Disposition', $disposition);

			return $response;
		}
	}
}
