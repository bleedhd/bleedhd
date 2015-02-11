<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Getunik\BleedHd\AssessmentDataBundle\Entity\ClientLogEntry;
use Getunik\BleedHd\AssessmentDataBundle\Handler\ClientLogEntryHandler;


/**
 * ClientLogEntriesController
 */
class ClientLogEntriesController extends FOSRestController
{
    protected $logEntryHandler;

    public function __construct(ClientLogEntryHandler $logEntryHandler)
    {
        $this->logEntryHandler = $logEntryHandler;
    }

    /**
     * @ParamConverter("logEntry", options={"id": "logEntry"})
     */
    public function getLogentryAction(ClientLogEntry $logEntry)
    {
        return $this->handleView($this->view($response));
    }

    /**
     * @Post("/logentries", requirements={"_format"="json|xml"})
     * @ParamConverter("logEntry", converter="fos_rest.request_body")
     */
    public function postLogentriesAction(ClientLogEntry $logEntry)
    {
        $this->logEntryHandler->save($logEntry);

        return $this->handleView($this->view($logEntry));
    }

    /**
     * @Post("/logentries/batch")
     * @ParamConverter("logEntries", converter="fos_rest.request_body", class="ArrayCollection<Getunik\BleedHd\AssessmentDataBundle\Entity\ClientLogEntry>")
     */
    public function postBatchAction($logEntries)
    {
        $this->logEntryHandler->batchInsert($logEntries);

        return $this->handleView($this->view(true));
    }
}
