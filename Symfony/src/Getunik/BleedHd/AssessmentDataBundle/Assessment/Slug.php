<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;


/**
 * Slug
 */
class Slug
{
    private $parent;
    private $current;
    private $full;

    public function __construct($current, Slug $parent = NULL)
    {
        $this->parent = $parent;
        $this->current = $current;

        $parts = array();
        if (!empty($parent))
        {
            array_push($parts, $parent->getFull());
        }
        if (!empty($current))
        {
            array_push($parts, $current);
        }

        $this->full = implode('.', $parts);
    }

    public function getShort()
    {
        return $this->current;
    }

    public function getFull()
    {
        return $this->full;
    }
}
