<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Handler\ResponseHandler;


/**
 * Class AssessmentContextIterator
 * @package Getunik\BleedHd\AssessmentDataBundle\Export
 */
class AssessmentContextIterator implements \Iterator
{
	private $assessments;
	private $questionnaire;
	private $responseHandler;

	private $index;
	private $contexts;

	public function __construct(array $assessments, array $questionnaire, ResponseHandler $responseHandler)
	{
		$this->assessments = $assessments;
		$this->questionnaire = $questionnaire;
		$this->responseHandler = $responseHandler;

		$this->index = 0;
		$this->contexts = [];
	}

	/**
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current()
	{
		if (!isset($this->contexts[$this->index])) {
			/** @var Assessment $assessment */
			$assessment = $this->assessments[$this->index];
			$responses = $this->responseHandler->getAssessmentResponses($assessment->getId());
			$this->contexts[$this->index] = new AssessmentContext($assessment, $this->questionnaire, $responses);
		}

		return $this->contexts[$this->index];
	}

	/**
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function next()
	{
		$this->index++;
	}

	/**
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	public function key()
	{
		return $this->index;
	}

	/**
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	public function valid()
	{
		return $this->index < count($this->assessments);
	}

	/**
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind()
	{
		$this->index = 0;
	}
}
