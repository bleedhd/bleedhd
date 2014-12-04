<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Patient;
use Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus;
use Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatusRepository;


/**
 * PatientsController
 */
class PatientStatusesController extends FOSRestController
{
    protected $patientStatusRepository;

    public function __construct(PatientStatusRepository $patientStatusRepository)
    {
        $this->patientStatusRepository = $patientStatusRepository;
    }

    /**
     * "bleed_get_patients"     [GET] /patients
     */
    public function getStatusesAction($patient)
    {
        return $this->handleView($this->view($this->patientStatusRepository->findBy(array('patientId' => $patient))));
    }

    /**
     * "get_patients"           [GET] /patients/{slug}
     *
     * @ParamConverter("status", options={"id": "status", "mapping": {"patient":"patientId","status":"id"}})
     */
    public function getStatusAction($patient, PatientStatus $status)
    {
        return $this->handleView($this->view($status));
    }

    /**
     * "post_users"             [POST] /patients
     *
     * @Post("/patients/{patient}/statuses", requirements={"_format"="json|xml"})
     * @ParamConverter("patient", options={"id" = "patient"})
     * @ParamConverter("status", converter="fos_rest.request_body")
     */
    public function postStatusesAction(Patient $patient, PatientStatus $status)
    {
        $status->setPatient($patient);

        $this->patientStatusRepository->save($status);

        return $this->handleView($this->view($status));

        //return $this->handleView($this->view("post statuses"));


        // $serializer = $this->get('fos_rest.serializer');
        // $status = $serializer->deserialize($request->getContent(), 'Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus', 'json');

        // $this->patientStatusRepository->save($status);

        // return $this->handleView($this->view($status));
    }

    /**
     * "put_user"               [PUT] /patients/{slug}
     *
     * @ParamConverter("status", options={"id": "status", "mapping": {"patient":"patientId","status":"id"}})
     * @ParamConverter("statusBody", converter="fos_rest.request_body")
     */
    public function putStatusAction($patient, PatientStatus $status, PatientStatus $statusBody)
    {
        $statusBody->setPatient($status->getPatient());
        $updated = $this->patientStatusRepository->update($statusBody);

        return $this->handleView($this->view($updated));
        // if ($statusBody->getId() != $slug)
        // {
        //     throw new HttpException(400, 'Resource ID mismatch: body -> ' . $statusBody->getId() . ', resource -> ' . $slug);
        // }

        // $updated = $this->patientStatusRepository->update($statusBody);

        // return $this->handleView($this->view($updated));
    }

    /**
     * "delete_user"            [DELETE] /patients/{slug}
     */
    public function deleteStatusAction($patient, $status)
    {
        return $this->handleView($this->view("delete status"));
    }
}
