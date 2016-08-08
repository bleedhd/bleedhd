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
        return isset($this->result[self::FIELD_META]) && $this->result[self::FIELD_META] === self::UNANSWERED;
    }

    public function isMultiValue()
    {
        return $this->hasValue() && !self::isAssoc($this->result['data']);
    }

	public function getData()
	{
		if ($this->hasValue())
		{
			return $this->result['data'];
		}

		return NULL;
	}

    public function getValue()
    {
        if ($this->hasValue())
        {
            return self::isAssoc($this->result['data']) ? $this->result['data']['value'] : $this->result['data'];
        }

        return NULL;
    }

	public function getMetaValue()
	{
		return isset($this->result[self::FIELD_META]) ? $this->result[self::FIELD_META] : NULL;
	}

    public function getSupplement($slug, $index = -1)
    {
        if (empty($this->result['data']))
        {
            return NULL;
        }

        $item = empty($this->result['data']) ? array() : $this->result['data'];

        if ($index >= 0)
        {
            $item = isset($this->result['data'][$index]) ? $this->result['data'][$index] : array();
        }

        return empty($this->result['data']) || empty($item['supplements']) || !isset($item['supplements'][$slug])
                ? NULL
                : $item['supplements'][$slug];
    }

    private static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
