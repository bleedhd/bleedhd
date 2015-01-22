<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;


/**
 * AssessmentsController
 */
class AssessmentsController extends FOSRestController
{
    protected $assessmentHandler;

    public function __construct(AssessmentHandler $assessmentHandler)
    {
        $this->assessmentHandler = $assessmentHandler;
    }

    /**
     */
    public function getAssessmentsAction($patient)
    {
        return $this->handleView($this->view($this->assessmentHandler->getPatientAssessments($patient)));
    }

    /**
     * @ParamConverter("assessment", options={"id": "assessment", "mapping": {"patient":"patientId","assessment":"id"}})
     */
    public function getAssessmentAction($patient, Assessment $assessment)
    {
        return $this->handleView($this->view($assessment));
    }

    /**
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
     */
    public function deleteAssessmentAction($patient, $assessment)
    {
        return $this->handleView($this->view("delete assessment"));
    }
}
