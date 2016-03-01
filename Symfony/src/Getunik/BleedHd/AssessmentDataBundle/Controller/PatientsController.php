<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Handler\PatientHandler;
use Getunik\BleedHd\AssessmentDataBundle\Handler\AssessmentHandler;


/**
 * PatientsController
 */
class PatientsController extends FOSRestController
{
    protected $patientHandler;
    protected $assessmentHandler;

    public function __construct(PatientHandler $patientHandler, AssessmentHandler $assessmentHandler)
    {
        $this->patientHandler = $patientHandler;
        $this->assessmentHandler = $assessmentHandler;
    }

    /**
     * @Security("has_role('ROLE_READER')")
     * @Put("/patients/progress", requirements={"_format"="json|xml"})
     * @ParamConverter("ids", converter="fos_rest.request_body", class="ArrayCollection<integer>")
     */
    public function putPatientsProgressAction($ids)
    {
        $progress = $this->assessmentHandler->getAssessmentProgress($ids);
        return $this->handleView($this->view($progress));
    }

    /**
     * @Security("has_role('ROLE_READER')")
     */
    public function getPatientsAction()
    {
        return $this->handleView($this->view($this->patientHandler->getAllPatients()));
    }

    /**
     * @Security("has_role('ROLE_READER')")
     * @ParamConverter("patient", options={"id" = "patient"})
     */
    public function getPatientAction(Patient $patient)
    {
        return $this->handleView($this->view($patient));
    }

    /**
     * @Security("has_role('ROLE_EDITOR')")
     * @Post("/patients", requirements={"_format"="json|xml"})
     * @ParamConverter("patient", converter="fos_rest.request_body")
     */
    public function postPatientsAction(Patient $patient)
    {
        $this->patientHandler->save($patient);

        return $this->handleView($this->view($patient));
    }

    /**
     * @Security("has_role('ROLE_EDITOR')")
     * @ParamConverter("patientBody", converter="fos_rest.request_body")
     */
    public function putPatientAction($patient, Patient $patientBody)
    {
        if ($patientBody->getId() != $patient)
        {
            throw new HttpException(400, 'Resource ID mismatch: body -> ' . $patientBody->getId() . ', resource -> ' . $slug);
        }

        $updated = $this->patientHandler->update($patientBody);

        return $this->handleView($this->view($updated));
    }

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN') or (has_role('ROLE_ADMIN') and is_granted('isOwner', patient))")
     * @ParamConverter("patient", options={"id" = "patient"})
     */
    public function deletePatientAction(Patient $patient)
    {
        $this->patientHandler->delete($patient);

        return $this->handleView($this->view(true));
    }
}
