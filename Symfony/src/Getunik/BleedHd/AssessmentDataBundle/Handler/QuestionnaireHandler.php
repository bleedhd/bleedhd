<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Symfony\Component\Yaml\Yaml;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;



/**
 * QuestionnaireHandler
 */
class QuestionnaireHandler
{
    private $questionnairesPath;

    public function __construct($questionnairesPath)
    {
        $this->questionnairesPath = $questionnairesPath;
    }

    public function getQuestionnaires()
    {
        $list = array();

        foreach (glob($this->questionnairesPath . '/*.yaml') as $fileName)
        {
            $list[] = basename($fileName, '.yaml');
        }

        return $list;
    }

    public function getQuestionnaireByName($name)
    {
        $filePath = $this->questionnairesPath . '/' . $name . '.yaml';

        if (file_exists($filePath))
        {
            return Yaml::parse(file_get_contents($filePath));
        }

        return NULL;
    }
}
