<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;
use Getunik\BleedHd\AssessmentDataBundle\Handler\ResponseHandler;

class ResponsesController extends FOSRestController
{
    protected $responseHandler;

    public function __construct(ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }

    /**
     * "bleed_get_assessments"     [GET] /assessments
     */
    public function getResponsesAction($patient, $assessment)
    {
        return $this->handleView($this->view($this->responseHandler->getAssessmentResponses($assessment)));
    }
}
