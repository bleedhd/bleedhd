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
    protected $settings;

    public function __construct(QuestionnaireHandler $questionnaireHandler, $settings)
    {
        $this->questionnaireHandler = $questionnaireHandler;
        $this->settings = $settings;
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

        if (!$this->supportsType($questionnaire))
        {
            throw new HttpException(404, "Unsupported assessment type '" . $questionnaire . "'");
        }

        return $this->handleView($this->view($data));
    }

    private function supportsType($type)
    {
        $allTypes = call_user_func_array("array_merge", $this->settings['allowed_assessment_types']);
        return array_search($type, $allTypes) !== false;
    }
}
