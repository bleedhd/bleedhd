<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Export;

use Symfony\Component\Yaml\Yaml;


class ExportConfig
{
	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var ColumnDefinition[]
	 */
	private $columns = NULL;

	/**
	 * Loads an export configuration for the given assessment type and export type.
	 *
	 * @param $basePath string base path of export type configurations
	 * @param $questionnaire string questionnaire / assessment type
	 * @param $exportType string name of the export type
	 * @return ExportConfig
	 * @throws \Exception if no such type configuration can be found
	 */
	public static function load($basePath, $questionnaire, $exportType)
	{
		$filePath = $basePath . '/' . $questionnaire . '/' . $exportType . '.yaml';
		if (!file_exists($filePath)) {
			throw new \Exception('Cannot find export type configuration "' . $exportType . '" for assessment type "' . $questionnaire . '"');
		}

		return new ExportConfig($filePath);
	}

	public static function getConfigurationMap($basePath, $questionnaires)
	{
		$map = [];

		foreach ($questionnaires as $questionnaire) {
			$types = [];

			foreach (glob($basePath . '/' . $questionnaire . '/*.yaml') as $fileName)
			{
				$config = new ExportConfig($fileName, false);
				$types[] = [
					'key' => basename($fileName, '.yaml'),
					'name' => $config->getName(),
				];
			}

			$map[$questionnaire] = $types;
		}

		return $map;
	}

	public function __construct($configPath, $process = true)
	{
		$this->config = $this->processFile($configPath, $process);
	}

	private function processFile($filePath, $processDirectives)
	{
		$data = Yaml::parse(file_get_contents($filePath));

		if ($processDirectives) {
			$this->searchDirectives($data, NULL, [
				'path' => $filePath,
				'ancestors' => [],
			]);
		}

		return $data;
	}

	private function searchDirectives(&$data, $index, $context)
	{
		$childContext = $context;
		$iterator = new \ArrayIterator($data);
		$current = [
			'index' => $index,
			'data' => &$data,
			'iterator' => $iterator
		];
		$childContext['ancestors'][] = &$current;

		while ($current['iterator']->valid()) {
			$index = $current['iterator']->key();
			$value = &$data[$index];

			if (is_array($value)) {
				if (isset($value['__directive'])) {
					$this->processDirective($value['__directive'], $index, $childContext);
				} else {
					$this->searchDirectives($value, $index, $childContext);
				}
			}

			$current['iterator']->next();
		}

		unset($value);
	}

	/**
	 * @param $directive array the directive to process
	 * @param $index integer the index in the current YAML object where the directive was found
	 * @param $context array the processing context of the directive
	 * @throws \Exception thrown if the given directive does not have a corresponding method
	 * @uses directiveInsert
	 */
	private function processDirective($directive, $index, $context)
	{
		$directiveMethod = 'directive' . ucfirst($directive['op']);
		if (!method_exists($this, $directiveMethod)) {
			throw new \Exception('Unknown directive operation "' . $directive['op'] . '"');
		}

		$this->{$directiveMethod}($directive, $index, $context);
	}

	private function directiveInsert($directive, $index, $context)
	{
		$ancestors = $context['ancestors'];
		// with inserts, the included data is inserted into the directive's parent array
		$targetContext = end($ancestors);
		$targetArray = &$targetContext['data'];
		$sourcePath = dirname($context['path']) . '/' . $directive['source'];

		$include = $this->processFile($sourcePath, true);
		if (!is_array($include) || self::isAssoc($include)) {
			// if the include is a single element (scalar or 'object'), then the item is simply used to replace
			// the array element containing the directive
			$include = [$include];
		}

		array_splice($targetArray, $index, 1, $include);
		// re-create the iterator and set its position to the end of the newly inserted elements
		$targetContext['iterator'] = new \ArrayIterator($targetArray);
		$targetContext['iterator']->seek($index + count($include) - 1);
	}

	/**
	 * @return ColumnDefinition[]
	 */
	private function loadColumns()
	{
		return array_map(function ($column) {
			return new ColumnDefinition($column);
		}, $this->config['columns']);
	}

	/**
	 * Returns the name of the export configuration.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->config['name'];
	}

	/**
	 * Returns the assessment type of the export configuration.
	 *
	 * @return string
	 */
	public function getAssessmentType()
	{
		return $this->config['type'];
	}

	/**
	 * Returns a lazily initialized array of column definitions
	 *
	 * @return ColumnDefinition[]
	 */
	public function getColumns()
	{
		if ($this->columns === NULL) {
			$this->columns = $this->loadColumns();
		}

		return $this->columns;
	}

	private static function isAssoc($arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
