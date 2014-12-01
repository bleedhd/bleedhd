<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     */
    public function getPatientAction($slug)
    {
    }

    /**
     * "post_users"             [POST] /patients
     */
    public function postPatientsAction(Request $request)
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
     */
    public function putPatientAction(Request $request, Patient $patient, Patient $patient2)
    {
    }

    /**
     * "delete_user"            [DELETE] /patients/{slug}
     */
    public function deletePatientAction($patient)
    {
    }
}
