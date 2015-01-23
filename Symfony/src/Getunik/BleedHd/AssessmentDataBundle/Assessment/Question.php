<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;


/**
 * Question
 */
class Question
{
    private $slug;
    private $question;
    private $result;

    public function __construct(Slug $slug, array $questionYaml, Response $response = NULL)
    {
        $this->slug = $slug;
        $this->question = $questionYaml;
        $this->result = new Result($response === NULL ? NULL : $response->getResult());
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function getResult()
    {
        return $this->result;
    }
}
