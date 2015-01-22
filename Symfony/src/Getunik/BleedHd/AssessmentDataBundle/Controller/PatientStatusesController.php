<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus;
use Getunik\BleedHd\AssessmentDataBundle\Handler\PatientStatusHandler;


/**
 * PatientStatusesController
 */
class PatientStatusesController extends FOSRestController
{
    protected $patientStatusHandler;

    public function __construct(PatientStatusHandler $patientStatusHandler)
    {
        $this->patientStatusHandler = $patientStatusHandler;
    }

    /**
     */
    public function getStatusesAction($patient)
    {
        return $this->handleView($this->view($this->patientStatusHandler->getPatientStatuses($patient)));
    }

    /**
     * @ParamConverter("status", options={"id": "status", "mapping": {"patient":"patientId","status":"id"}})
     */
    public function getStatusAction($patient, PatientStatus $status)
    {
        return $this->handleView($this->view($status));
    }

    /**
     * @Post("/patients/{patient}/statuses", requirements={"_format"="json|xml"})
     * @ParamConverter("patient", options={"id" = "patient"})
     * @ParamConverter("status", converter="fos_rest.request_body")
     */
    public function postStatusesAction(Patient $patient, PatientStatus $status)
    {
        $status->setPatient($patient);

        $this->patientStatusHandler->save($status);

        return $this->handleView($this->view($status));
    }

    /**
     * @ParamConverter("status", options={"id": "status", "mapping": {"patient":"patientId","status":"id"}})
     * @ParamConverter("statusBody", converter="fos_rest.request_body")
     */
    public function putStatusAction($patient, PatientStatus $status, PatientStatus $statusBody)
    {
        $statusBody->setPatient($status->getPatient());
        $updated = $this->patientStatusHandler->update($statusBody);

        return $this->handleView($this->view($updated));
    }

    /**
     */
    public function deleteStatusAction($patient, $status)
    {
        return $this->handleView($this->view("delete status"));
    }
}
