<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;

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
     * "bleed_get_responses"     [GET] /patient/{patient}/assessment/{assessment}/responses
     */
    public function getResponsesAction($patient, $assessment)
    {
        return $this->handleView($this->view($this->responseHandler->getAssessmentResponses($assessment)));
    }

    /**
     * "bleed_get_response"           [GET] /patient/{patient}/assessment/{assessment}/responses/{response}
     *
     * @Get("/patients/{patient}/assessments/{assessment}/responses/{response}", requirements={"response"=".*(?=\.json$|\.xml$)|.*"})
     * @ParamConverter("response", options={"id": "response", "mapping": {"assessment":"assessmentId","response":"questionSlug"}})
     */
    public function getResponseAction($patient, $assessment, Response $response)
    {
        return $this->handleView($this->view($response));
    }

    /**
     * "bleed_post_response"             [POST] /patient/{patient}/assessment/{assessment}/responses
     *
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
     * "put_user"               [PUT] /patient/{patient}/assessment/{assessment}/responses/{response}
     *
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
     * "bleed_delete_response"            [DELETE] /patient/{patient}/assessment/{assessment}/responses/{response}
     *
     * @Delete("/patients/{patient}/assessments/{assessment}/responses/{response}", requirements={"response"=".*(?=\.json$|\.xml$)|.*"})
     */
    public function deleteResponseAction($patient, $assessment, $response)
    {
        return $this->handleView($this->view("delete response"));
    }
}
