<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;

use Getunik\BleedHd\AssessmentDataBundle\Handler\QuestionnaireHandler;


/**
 * QuestionnairesController
 */
class QuestionnairesController extends FOSRestController
{
    protected $questionnaireHandler;

    public function __construct(QuestionnaireHandler $questionnaireHandler)
    {
        $this->questionnaireHandler = $questionnaireHandler;
    }

    /**
     * @Security("has_role('ROLE_READER')")
     */
    public function getQuestionnairesAction()
    {
        return $this->handleView($this->view($this->questionnaireHandler->getQuestionnaires()));
    }

    /**
     * @Security("has_role('ROLE_READER')")
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
