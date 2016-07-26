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
		$this->config = Yaml::parse(file_get_contents($configPath));
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
}
