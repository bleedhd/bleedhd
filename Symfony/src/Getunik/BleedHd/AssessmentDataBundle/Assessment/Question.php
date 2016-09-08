<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;


/**
 * Question
 */
class Question
{
	private $slug;
	private $question;
	private $result;

	public function __construct(Slug $slug, array $questionYaml, Result $result)
	{
		$this->slug = $slug;
		$this->question = $questionYaml;
		$this->result = $result;
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
