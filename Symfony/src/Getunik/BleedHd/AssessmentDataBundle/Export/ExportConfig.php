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

	public function __construct($configPath)
	{
		$this->config = $this->processFile($configPath);
	}

	private function processFile($filePath)
	{
		$data = Yaml::parse(file_get_contents($filePath));

		$this->searchDirectives($data, NULL, [
			'path' => $filePath,
			'ancestors' => [],
		]);

		return $data;
	}

	private function searchDirectives(&$data, $index, $context)
	{
		$childContext = $context;
		$iterator = new \ArrayIterator($data);
		$childContext['ancestors'][] = [
			'index' => $index,
			'data' => &$data,
			'iterator' => $iterator
		];

		while ($iterator->valid()) {
			$index = $iterator->key();
			$value = &$data[$index];

			if (is_array($value)) {
				if (isset($value['__directive'])) {
					$this->processDirective($value['__directive'], $index, $childContext);
				} else {
					$this->searchDirectives($value, $index, $childContext);
				}
			}

			$iterator->next();
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
		$targetIterator = $targetContext['iterator'];
		$sourcePath = dirname($context['path']) . '/' . $directive['source'];

		$include = $this->processFile($sourcePath);
		if (!is_array($include) || self::isAssoc($include)) {
			// if the include is a single element (scalar or 'object'), then the item is simply used to replace
			// the array element containing the directive
			$include = [$include];
		}

		array_splice($targetArray, $index, 1, $include);
		// set iterator position to the end of the newly inserted elements
		$targetIterator->seek($index + count($include) - 1);
	}

	private function loadColumns()
	{
		return array_map(function ($column) { return new ColumnDefinition($column); }, $this->config['columns']);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->config['name'];
	}

	/**
	 * @return string
	 */
	public function getAssessmentType()
	{
		return $this->config['type'];
	}

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
