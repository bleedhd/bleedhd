<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Entity\PatientRepository;


/**
 * PatientsController
 */
class PatientsController extends FOSRestController
{
    protected $patientRepository;
    protected $patientManager;

    public function __construct(PatientRepository $patientRepository, $patientManager)
    {
        $this->patientRepository = $patientRepository;
        $this->patientManager = $patientManager;
    }

    /**
     * "bleed_get_patients"     [GET] /patients
     */
    public function getPatientsAction()
    {
        return $this->handleView($this->view($this->patientRepository->findAll()));
    }

    /**
     * "get_patients"           [GET] /patients/{slug}
     *
     * @ParamConverter("patient", options={"id" = "patient"})
     */
    public function getPatientAction(Patient $patient)
    {
        return $this->handleView($this->view($patient));
    }

    /**
     * "post_users"             [POST] /patients
     */
    public function postPatientsAction()
    {
        $serializer = $this->get('fos_rest.serializer');
        $patient = $serializer->deserialize($request->getContent(), 'Getunik\BleedHd\AssessmentDataBundle\Entity\Patient', 'json');

        $patient->setLastUpdatedDate(new \DateTime());
        $patient->setLastUpdatedBy($this->getUser()->getId());

        $this->patientRepository->save($patient);

        return $this->handleView($this->view($patient));
    }

    /**
     * "put_user"               [PUT] /patients/{slug}
     *
     * @ParamConverter("patientBody", converter="fos_rest.request_body")
     */
    public function putPatientAction($patient, Patient $patientBody)
    {
        if ($patientBody->getId() != $patient)
        {
            throw new HttpException(400, 'Resource ID mismatch: body -> ' . $patientBody->getId() . ', resource -> ' . $slug);
        }

        $updated = $this->patientRepository->update($patientBody);

        return $this->handleView($this->view($updated));
    }

    /**
     * "delete_user"            [DELETE] /patients/{slug}
     */
    public function deletePatientAction($patient)
    {
    }
}
