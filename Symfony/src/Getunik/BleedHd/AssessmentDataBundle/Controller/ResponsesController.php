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

use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;
use Getunik\BleedHd\AssessmentDataBundle\Handler\ResponseHandler;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;


/**
 * ResponsesController
 */
class ResponsesController extends FOSRestController
{
    protected $responseHandler;
    protected $assessmentHandler;

    public function __construct(ResponseHandler $responseHandler, AssessmentHandler $assessmentHandler)
    {
        $this->responseHandler = $responseHandler;
        $this->assessmentHandler = $assessmentHandler;
    }

    /**
     */
    public function getResponsesAction($patient, $assessment)
    {
        return $this->handleView($this->view($this->responseHandler->getAssessmentResponses($assessment)));
    }

    /**
     * @Get("/patients/{patient}/assessments/{assessment}/responses/{response}", requirements={"response"=".*(?=\.json$|\.xml$)|.*"})
     * @ParamConverter("response", options={"id": "response", "mapping": {"assessment":"assessmentId","response":"questionSlug"}})
     */
    public function getResponseAction($patient, $assessment, Response $response)
    {
        return $this->handleView($this->view($response));
    }

    /**
     * @Post("/patients/{patient}/assessments/{assessment}/responses", requirements={"response"=".*(?=\.json$|\.xml$)|.*"})
     * @ParamConverter("assessment", options={"id" = "assessment"})
     * @ParamConverter("response", converter="fos_rest.request_body")
     */
    public function postResponsesAction($patient, Assessment $assessment, Response $response)
    {
        $response->setAssessment($assessment);
        $this->responseHandler->save($response);

        return $this->handleView($this->view($response));
    }

    /**
     * @Put("/patients/{patient}/assessments/{assessment}/responses/{response}", requirements={"response"=".*(?=\.json$|\.xml$)|.*"})
     * @ParamConverter("response", options={"id": "response", "mapping": {"assessment":"assessmentId","response":"questionSlug"}})
     * @ParamConverter("responseBody", converter="fos_rest.request_body")
     */
    public function putResponseAction($patient, $assessment, Response $response, Response $responseBody)
    {
        $responseBody->setAssessment($response->getAssessment());
        $updated = $this->responseHandler->update($responseBody);

        return $this->handleView($this->view($updated));
    }

    /**
     * @Delete("/patients/{patient}/assessments/{assessment}/responses/{response}", requirements={"response"=".*(?=\.json$|\.xml$)|.*"})
     */
    public function deleteResponseAction($patient, $assessment, $response)
    {
        return $this->handleView($this->view("delete response"));
    }

    /**
     * @Post("/patients/{patient}/assessments/{assessment}/responses/batch")
     * @ParamConverter("assessment", options={"id" = "assessment"})
     * @ParamConverter("responsesBody", converter="fos_rest.request_body", class="ArrayCollection<Getunik\BleedHd\AssessmentDataBundle\Entity\Response>")
     */
    public function postBatchAction($patient, Assessment $assessment, $responsesBody)
    {
        $this->responseHandler->batchUpdate($assessment, $responsesBody);
        $this->assessmentHandler->updateScore($assessment);

        return $this->handleView($this->view($responsesBody));
    }
}
