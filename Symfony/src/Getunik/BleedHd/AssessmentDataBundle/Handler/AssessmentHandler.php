<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment;
use Getunik\BleedHd\AssessmentDataBundle\Assessment\AssessmentContext;
use Getunik\BleedHd\AssessmentDataBundle\Entity\AssessmentRepository;
use Getunik\BleedHd\AssessmentDataBundle\Scoring\ScoreCalculatorFactory;


/**
 * AssessmentHandler
 */
class AssessmentHandler
{
	const ENTITY_TARGET_MAP = [
		'patient' => 'p',
		'assessment' => 'a',
	];

	public static $entityType = 'Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment';

	/**
	 * @var AssessmentRepository
	 */
	private $repository;
	/**
	 * @var ResponseHandler
	 */
	private $responseHandler;
	/**
	 * @var QuestionnaireHandler
	 */
	private $questionnaireHandler;
	/**
	 * @var ScoreCalculatorFactory
	 */
	private $scoreCalculatorFactory;

	public function __construct(ManagerRegistry $managerRegistry, ResponseHandler $responseHandler, QuestionnaireHandler $questionnaireHandler, ScoreCalculatorFactory $scoreCalculatorFactory)
	{
		$this->repository = $managerRegistry
			->getManagerForClass(self::$entityType)
			->getRepository(self::$entityType);

		$this->responseHandler = $responseHandler;
		$this->questionnaireHandler = $questionnaireHandler;
		$this->scoreCalculatorFactory = $scoreCalculatorFactory;
	}

	public function save(Assessment $assessment)
	{
		$this->repository->save($assessment, true);
	}

	public function update(Assessment $assessment)
	{
		return $this->repository->update($assessment);
	}

	public function delete(Assessment $assessment)
	{
		$assessment->setIsDeleted(true);
		return $this->update($assessment);
	}

	public function restore(Assessment $assessment)
	{
		$assessment->setIsDeleted(false);
		return $this->update($assessment);
	}

	public function getPatientAssessments($patientId)
	{
		return $this->repository->findBy(array('patientId' => $patientId, 'isDeleted' => 0));
	}

	public function updateScore(Assessment $assessment)
	{
		$responses = $this->responseHandler->getAssessmentResponses($assessment->getId());
		$questionnaire = $this->questionnaireHandler->getQuestionnaireByName($assessment->getQuestionnaire());

		$context = new AssessmentContext($assessment, $questionnaire, $responses);
		$calculator = $this->scoreCalculatorFactory->create($assessment->getQuestionnaire());

		$result = $calculator->run($context)->getResult();
		$assessment->setResult($result);
		$assessment->setQuestionnaireVersion($context->getQuestionnaireVersion());
		$this->repository->save($assessment, false);

		return $assessment;
	}

	/**
	 * Creates an associative array from patient IDs to their overall assessment status. The status indicates whether
	 * all of the patients assessments are completed (have a total score) or not.
	 *
	 * @param array $patientIds - a list of patient IDs
	 * @return array - a mapping from patient IDs to their assessment status
	 */
	public function getAssessmentProgress(array $patientIds)
	{
		$result = array();
		foreach ($patientIds as $id) {
			$result[$id] = array('patient_id' => $id, 'progress' => Assessment::PROGRESS_NONE);
		}

		$assessments = $this->repository->getPatientProgress($patientIds);
		foreach ($assessments as $assessment) {
			$patient = &$result[$assessment['patientId']];

			if ($assessment['progress'] === Assessment::PROGRESS_COMPLETE && $patient['progress'] === Assessment::PROGRESS_NONE) {
				$patient['progress'] = Assessment::PROGRESS_COMPLETE;
			} else if ($assessment['progress'] === Assessment::PROGRESS_TENTATIVE || $assessment['progress'] === NULL) {
				$patient['progress'] = Assessment::PROGRESS_TENTATIVE;
			}
		}

		return array_values($result);
	}

	/**
	 * @param $filterSpec array @see getFilteredAssessments
	 * @return array a list of questionnaires and counts
	 */
	public function countFilteredAssessments($filterSpec)
	{
		$builder = $this->getFilterQuery($filterSpec);
		$x = $builder->expr();

		$builder
			->select('a.questionnaire')
			->addSelect($x->count('a.questionnaire') . ' AS num')
			->groupBy('a.questionnaire');

		$query = $builder->getQuery();
		return $query->getArrayResult();
	}

	/**
	 * Returns a list of assessments filterd by the given filter specification.
	 *
	 * @param $filterSpec array the assessment filter specification; this sequence of conditions will be
	 * translated into a Doctrine query for assessments that should be exported.
	 * 	[ - array of filter conditions
	 * 		[
	 * 			'target' - target entity type for the conditions; see @see self::ENTITY_TARGET_MAP
	 * 			'property' - name of the entity property to which the condition should apply
	 * 			'op' - Doctrine expression operator ('eq', 'gt', 'like', ...) for the condition
	 * 			'value' - the value against which the condition is checked
	 * 		]
	 * 	]
	 * @param null $questionnaire string if specified, the returned assessments are additionally limited to the given
	 * questionnaire / assessment type
	 * @return Assessment[]
	 */
	public function getFilteredAssessments($filterSpec, $questionnaire = NULL)
	{
		$builder = $this->getFilterQuery($filterSpec);

		if ($questionnaire) {
			$builder->andWhere($builder->expr()->eq('a.questionnaire', $builder->expr()->literal($questionnaire)));
		}

		$query = $builder->getQuery();
		return $query->getResult();
	}

	/**
	 * @param $filterSpec
	 * @return QueryBuilder
	 */
	private function getFilterQuery($filterSpec)
	{
		$builder = $this->repository->createQueryBuilder('a');
		$x = $builder->expr();

		$builder->join('a.patient', 'p');

		$baseConditions = $x->andX(
			$x->eq('p.isDeleted', $x->literal(false)),
			$x->eq('a.isDeleted', $x->literal(false))
		);

		$filter = $builder->expr()->andX(
			$baseConditions,
			$this->buildQueryFromFilterSpec($builder, $filterSpec)
		);

		$builder->where($filter);

		return $builder;
	}

	private function buildQueryFromFilterSpec(QueryBuilder $builder, $filterSpec)
	{
		$x = $builder->expr();
		$map = self::ENTITY_TARGET_MAP;
		$group = $x->andX();

		foreach ($filterSpec as $index => $condition) {

			if (count($condition) > 4) {
				throw new \Exception('Condition #' . $index . ' contains too many attributes');
			}

			if (!isset($condition['target']) || !isset($map[$condition['target']])) {
				throw new \Exception('Invalid condition target in condition #' . $index);
			}

			if (!isset($condition['property'])) {
				throw new \Exception('Missing condition property in condition #' . $index);
			}

			if (!isset($condition['op']) || !method_exists($x, $condition['op'])) {
				throw new \Exception('Invalid condition operator in condition #' . $index);
			}

			if (!isset($condition['value'])) {
				throw new \Exception('Missing condition value in condition #' . $index);
			}

			// since we know the values are there and safe, we can just extract them from the associative array
			/** @var string $target */
			/** @var string $property */
			/** @var string $op */
			/** @var mixed $value */
			extract($condition);

			$paramName = ':' . implode('_', [$target, $property]);
			$fieldName = $map[$target] . '.' . $property;

			// this only works for binary operators - at some point this should probably be extended
			$group->add($x->{$op}($fieldName, $paramName));
			$builder->setParameter($paramName, $value);

		}

		return $group;
	}
}
