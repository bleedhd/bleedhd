<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

/**
 * PatientsController
 */
class PatientsController extends FOSRestController
{
    protected $patientManager;

    public function __construct($patientManager)
    {
        $this->patientManager = $patientManager;
    }

    /**
     * "bleed_get_patients"     [GET] /patients
     */
    public function getPatientsAction()
    {
        return $this->handleView($this->view(array('bla' => 'gurra', 'res' => get_class($this->patientManager))));
    }
}
