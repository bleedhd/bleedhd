<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Handler\PatientHandler;


/**
 * PatientsController
 */
class PatientsController extends FOSRestController
{
    protected $patientHandler;

    public function __construct(PatientHandler $patientHandler)
    {
        $this->patientHandler = $patientHandler;
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
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deletePatientAction($patient)
    {
        return $this->handleView($this->view("delete patient"));
    }
}
