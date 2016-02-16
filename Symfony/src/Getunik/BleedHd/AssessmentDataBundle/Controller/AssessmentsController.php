<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("has_role('ROLE_READER')")
     */
    public function getAssessmentsAction($patient)
    {
        return $this->handleView($this->view($this->assessmentHandler->getPatientAssessments($patient)));
    }

    /**
     * @Security("has_role('ROLE_READER')")
     * @ParamConverter("assessment", options={"id": "assessment", "mapping": {"patient":"patientId","assessment":"id"}})
     */
    public function getAssessmentAction($patient, Assessment $assessment)
    {
        return $this->handleView($this->view($assessment));
    }

    /**
     * @Security("has_role('ROLE_EDITOR')")
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
     * @Security("has_role('ROLE_EDITOR')")
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
     * @Security("has_role('ROLE_SUPER_ADMIN') or (has_role('ROLE_ADMIN') and is_granted('isOwner', assessment))")
     * @ParamConverter("assessment", options={"id" = "assessment"})
     */
    public function deleteAssessmentAction($patient, Assessment $assessment)
    {
        $this->assessmentHandler->delete($assessment);

        return $this->handleView($this->view(true));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Get("/patients/{patient}/assessments/{assessment}/score")
     * @ParamConverter("assessment", options={"id" = "assessment"})
     */
    /*public function getScoreAction($patient, Assessment $assessment)
    {
        //return $this->handleView($this->view("bla"));
        $this->assessmentHandler->updateScore($assessment);
        return $this->handleView($this->view(json_decode(json_encode($assessment->getResult()), true)));
    }*/
}
