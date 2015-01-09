<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Handler\QuestionnaireHandler;


/**
 * QuestionnairesController
 */
class QuestionnairesController extends FOSRestController
{
    protected $questionnaireHandler;
    protected $patientManager;

    public function __construct(QuestionnaireHandler $questionnaireHandler)
    {
        $this->questionnaireHandler = $questionnaireHandler;
    }

    /**
     * "bleed_get_questionnaires"     [GET] /questionnaires
     */
    public function getQuestionnairesAction()
    {
        return $this->handleView($this->view($this->questionnaireHandler->getQuestionnaires()));
    }

    /**
     * "get_questionnaire"           [GET] /questionnaires/{slug}
     */
    public function getQuestionnaireAction($questionnaire)
    {
        $data = $this->questionnaireHandler->getQuestionnaireByName($questionnaire);

        if ($data === NULL)
        {
            throw new HttpException(404, "Questionnaire '" . $questionnaire . "' does not exist");
        }

        return $this->handleView($this->view($data));
    }
}
