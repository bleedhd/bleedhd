<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Scoring;

use Getunik\BleedHd\AssessmentDataBundle\Assessment\Question;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\Slug;



/**
 * ScoreMapping
 */
class ScoreMapping
{
	private $slug;
	private $value;
	private $config;
	private $children;

	public function __construct(Slug $slug, $config = NULL, $value)
	{
		$this->slug = $slug;
		$this->config = $config;
		$this->value = $value;
		$this->children = array();
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function hasConfig()
	{
		return $this->config !== NULL;
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function addChild(ScoreMapping $child)
	{
		$this->children[] = $child;
	}

	public function getChildren()
	{
		return $this->children;
	}
}
