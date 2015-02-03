<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;


/**
 * Result
 */
class Result
{
    const FIELD_META = 'meta';

    const UNANSWERED = 'nya';

    private $result;

    public function __construct(array $result = NULL)
    {
        if ($result === NULL)
        {
            $this->result = array(
                self::FIELD_META => self::UNANSWERED,
            );
        }
        else
        {
            $this->result = $result;
        }
    }

    public function hasValue()
    {
        return !empty($this->result['data']);
    }

    public function isUnanswered()
    {
        return isset($this->result['meta']) && $this->result['meta'] === self::UNANSWERED;
    }

    public function isMultiValue()
    {
        return $this->hasValue() && !self::isAssoc($this->result['data']);
    }

    public function getValue()
    {
        if ($this->hasValue() && self::isAssoc($this->result['data']))
        {
            return $this->result['data']['value'];
        }

        return NULL;
    }

    public function getSupplement($slug)
    {
        return empty($this->result['data']) || empty($this->result['data']['supplements']) || !isset($this->result['data']['supplements'][$slug])
                ? NULL
                : $this->result['data']['supplements'][$slug];
    }

    private static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
