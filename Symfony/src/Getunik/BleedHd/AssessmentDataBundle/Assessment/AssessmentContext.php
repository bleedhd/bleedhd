<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Assessment;

use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;


/**
 * AssessmentContext
 */
class AssessmentContext
{
	private static $hierarchy = array('chapters', 'sections', 'screens', 'questions');

	private $assessment;
	private $questions = array();
	private $questionnaireVersion;
	private $questionMap = NULL;
	private $resultType;

	public function __construct(Assessment $assessment, array $questionnaire, array $responses, $resultType = Result::class)
	{
		$this->assessment = $assessment;
		$this->resultType = $resultType;
		$this->processQuestionnaire($questionnaire, $responses);
	}

	protected function processQuestionnaire(array $questionnaire, array $responses)
	{
		$this->questionnaireVersion = isset($questionnaire['version']) ? $questionnaire['version'] : 'unknown';
		$responseMap = array();

		foreach ($responses as $response) {
			$responseMap[$response->getQuestionSlug()] = $response;
		}

		$rootSlug = new Slug(isset($questionnaire['slug']) ? $questionnaire['slug'] : $this->assessment->getQuestionnaire(), NULL);

		$this->processHierarchySegment($questionnaire, $responseMap, 0, $rootSlug);
	}

	protected function processHierarchySegment(array $segmentData, array $responseMap, $segmentIndex, Slug $slug = NULL)
	{
		$segmentName = self::$hierarchy[$segmentIndex];
		$isLast = ($segmentIndex === count(self::$hierarchy) - 1);

		foreach ($segmentData[$segmentName] as $subSegment) {
			$subSlug = new Slug(isset($subSegment['slug']) ? $subSegment['slug'] : NULL, $slug);
			if ($isLast) {
				if (isset($subSegment['type']) && $subSegment['type'] === 'multi') {
					$this->processHierarchySegment($subSegment, $responseMap, $segmentIndex, $subSlug);
				} else {
					$response = isset($responseMap[$subSlug->getFull()]) ? $responseMap[$subSlug->getFull()] : NULL;
					$this->questions[] = new Question($subSlug, $subSegment, new $this->resultType($response === NULL ? NULL : $response->getResult()));
				}
			} else {
				$this->processHierarchySegment($subSegment, $responseMap, $segmentIndex + 1, $subSlug);
			}
		}
	}

	public function getAssessment()
	{
		return $this->assessment;
	}

	public function getQuestions()
	{
		return $this->questions;
	}

	public function getQuestionnaireVersion()
	{
		return $this->questionnaireVersion;
	}

	/**
	 * @return Question[]|null an associative array from question slugs to Question objects
	 */
	public function getQuestionMap()
	{
		if ($this->questionMap === NULL) {
			$this->questionMap = [];
			foreach ($this->getQuestions() as $question) {
				/** @var $question Question */
				$this->questionMap[$question->getSlug()->getFull()] = $question;
			}
		}

		return $this->questionMap;
	}

	/**
	 * @param $slug string
	 * @return Question|null the question for the given slug
	 */
	public function getQuestion($slug)
	{
		$map = $this->getQuestionMap();

		return isset($map[$slug]) ? $map[$slug] : NULL;
	}
}
