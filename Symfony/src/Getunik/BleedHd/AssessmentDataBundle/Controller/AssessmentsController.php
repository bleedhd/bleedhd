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
}
