<?php

namespace App\Reporting\Resources;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Filters\Filter;
use App\Reporting\Form;
use App\Reporting\Processing\QueryBuilder;
use App\Reporting\Processing\QueryGroup;
use App\Reporting\Processing\QueryPath;
use App\Reporting\ReportField;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;

class ReportConfig
{
	/** @var string */
	protected $name;
//	/** @var QueryGroup */
//	protected $primary_query_group;
	/** @var TableList */
	protected $tables;

	/** @var array ReportField[] */
	protected $report_fields = [];

	/** @var array Filter[] */
	protected $report_filters = [];

	/** @var QueryGroup[] */
	protected $query_groups = [];

	/**
	 * ReportConfig constructor.
	 * @param string $name
	 * @param Table $primary_resource
	 */
	public function __construct($name, Table $primary_resource)
	{
		$this->name = $name;
//		var_dump($primary_resource);
//		$primary_resource->setAsPrimary();
//		var_dump($primary_resource);
		$this->query_groups[] = new QueryGroup($primary_resource, true);

	}

	public function __debugInfo() {
		return [
			'query_groups' => $this->query_groups,
			'tables' => $this->tables,
		];
	}

	public function generateFieldsAndFilters()
	{
		foreach ($this->query_groups as $group) {
			$this->report_fields = array_merge($this->report_fields, $group->getReportFields());

			$this->report_filters = array_merge($this->report_filters, $group->getReportFilters());
		}
	}

	public function generateFields()
	{
		foreach ($this->query_groups as $group) {
			$this->report_fields = array_merge($this->report_fields, $group->getReportFields());
		}
	}

	public function generateFilters()
	{
		foreach ($this->query_groups as $group) {
			$this->report_filters = array_merge($this->report_filters, $group->getReportFilters());
		}
	}

	public function form()
	{
		return new Form($this->report_fields, $this->report_filters);
	}

	public function addReportField(DatabaseField $field, $selectable_overrides = null)
	{
		$field = new ReportField($field, $selectable_overrides);
		$this->report_fields[] = $field;
	}

	public function addFilter(DatabaseField $db_field, $label = null)
	{
		$filter = new Filter($db_field, $label);
		$this->report_filters[] = $filter;
	}

	public function addTable(Table $table)
	{
		$relation_table = $this->findFirstMatching($table->getRelationshipAliases());
//		var_dump($relation_table);
		if ($relation_table) {
			$is_child = $table->connectTable($relation_table);

			if ($is_child) {
				$this->query_groups[] = new QueryGroup($table);
			} else {
				$query_group = $this->findQueryGroupByTable($relation_table);
				$query_group->addTable($table);
			}
//			var_dump($table);
			return;
		}

		throw new \LogicException('Could not find path of required table(s) for '.$table->alias());
	}

	/**
	 * @param SelectionsInterface $selections
	 * @return string
	 */
	public function generateSql(SelectionsInterface $selections)
	{
		$query_builder = new QueryBuilder($selections, $this->query_groups);

		return $query_builder->buildQuery();
	}

	protected function findFirstMatching($table_aliases)
	{
		foreach ($this->query_groups as $group) {
			foreach ($table_aliases as $alias) {
				$table = $group->getTable($alias);
				if ($table) {
					return $table;
				}
			}
		}
	}

//	protected function buildMainQuery($group, $required_paths)
//	{
//		return $this->buildQuery($group, $required_paths);
//	}
//
//	protected function buildSubQuery($group, $required_paths)
//	{
//		return ' LEFT JOIN ('.$this->buildQuery($group, $required_paths, true).') AS sub_query_'.$group.' ON sub_query_'.$group.'.asset_id = asset.id';
//	}

	/**
	 * @param Table $table
	 * @return QueryGroup
	 */
	protected function findQueryGroupByTable(Table $table)
	{
		foreach ($this->query_groups as $group) {
			if ($group->hasTable($table)) {
				return $group;
			}
		}
	}

	/**
	 * @param SelectedField[] $fields
	 * @param SelectedFilter[] $filters
	 * @return mixed
	 */
//	protected function getRequiredTables($fields, $filters)
//	{
//		$paths = $this->getRequiredPaths($fields, $filters);
//
//		return $this->getTablesFromPaths($paths);
//	}

	/**
	 * @param SelectedField[] $fields
	 * @param SelectedFilter[] $filters
	 * @return mixed
	 */
//	protected function getRequiredGroups($fields, $filters)
//	{
//		$paths = $this->getRequiredPaths($fields, $filters);
//
//		return $this->getGroupsFromPaths($paths);
//	}

	/**
	 * @param SelectedField[] $fields
	 * @param SelectedFilter[] $filters
	 * @return mixed
	 */
//	protected function getRequiredPaths($fields, $filters)
//	{
//		$tables = [];
//		foreach ($fields as $field) {
//			$tables[$field->table()] = $field->table();
//		}
//		foreach ($filters as $filter) {
//			$tables[$filter->table()] = $filter->table();
//		}
//
//		$paths = array_intersect_key($this->relationship_paths, $tables);
//
//		return $paths;
//	}

//	protected function getTablesFromPaths($paths)
//	{
//		$tables = [];
//		foreach ($paths as $path) {
//			$tables = $tables + $path;
//		}
//
//		return $tables;
//	}

//	protected function getGroupsFromPaths($paths)
//	{
//		$groups = [];
//		foreach ($paths as $path) {
//			$groups[] = $this->getGroupFromPath($path);
//		}
//
//		return array_unique($groups);
//	}
//
//	protected function getGroupFromTable($table_alias)
//	{
//		return $this->getGroupFromPath($this->relationship_paths[$table_alias]);
//	}
//
//	protected function getGroupFromPath($path)
//	{
//		while (count($path) > 0) {
//			$table = array_pop($path);
//			foreach ($this->table_groups as $group) {
//				if ($table == $group) {
//					return $group;
//				}
//			}
//		}
//	}
//
//	protected function filterPathsUsingGroup($group, $paths)
//	{
//		$filtered_paths = [];
//		foreach ($paths as $path) {
//			if ($group == $this->getGroupFromPath($path)) {
//				$filtered_paths[] = $path;
//			}
//		}
//		return $filtered_paths;
//	}
}