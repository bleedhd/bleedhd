<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;

class AssessmentsController extends FOSRestController
{
    protected $assessmentHandler;

    public function __construct(AssessmentHandler $assessmentHandler)
    {
        $this->assessmentHandler = $assessmentHandler;
    }

    /**
     * "bleed_get_assessments"     [GET] /assessments
     */
    public function getAssessmentsAction($patient)
    {
        return $this->handleView($this->view($this->assessmentHandler->getPatientAssessments($patient)));
    }

    /**
     * "bleed_get_assessment"           [GET] /assessment/{slug}
     *
     * @ParamConverter("assessment", options={"id": "assessment", "mapping": {"patient":"patientId","assessment":"id"}})
     */
    public function getAssessmentAction($patient, Assessment $assessment)
    {
        return $this->handleView($this->view($assessment));
    }

    /**
     * "bleed_post_assessments"             [POST] /patients
     *
     * @Post("/patients/{patient}/assessments", requirements={"_format"="json|xml"})
     * @ParamConverter("patient", options={"id" = "patient"})
     * @ParamConverter("assessment", converter="fos_rest.request_body")
     */
    public function postAssessmentsAction(Patient $patient, Assessment $assessment)
    {
        $assessment->setPatient($patient);

        $this->assessmentHandler->save($assessment);

        return $this->handleView($this->view($assessment));
    }

    /**
     * "put_user"               [PUT] /patients/{slug}
     *
     * @ParamConverter("assessment", options={"id": "assessment", "mapping": {"patient":"patientId","assessment":"id"}})
     * @ParamConverter("assessmentBody", converter="fos_rest.request_body")
     */
    public function putAssessmentAction($patient, Assessment $assessment, Assessment $assessmentBody)
    {
        $assessmentBody->setPatient($assessment->getPatient());
        $updated = $this->assessmentHandler->update($assessmentBody);

        return $this->handleView($this->view($updated));
    }

    /**
     * "delete_user"            [DELETE] /patients/{slug}
     */
    public function deleteAssessmentAction($patient, $assessment)
    {
        return $this->handleView($this->view("delete assessment"));
    }
}
