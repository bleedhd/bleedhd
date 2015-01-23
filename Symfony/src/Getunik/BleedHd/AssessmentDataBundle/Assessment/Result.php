<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;


/**
 * Result
 */
class Result
{
    private $result;

    public function __construct(array $result = NULL)
    {
        $this->result = $result;
    }

    public function hasValue()
    {
        return !empty($this->result['data']);
    }
}
