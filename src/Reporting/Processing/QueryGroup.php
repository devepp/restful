<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/17/2019
 * Time: 11:00 AM
 */

namespace App\Reporting\Processing;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Filters\Filter;
use App\Reporting\ReportField;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableList;
use App\Reporting\Selectables\Standard;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;

class QueryGroup
{
	/** @var bool */
	protected $is_primary;
	/** @var Table */
	protected $root;
	/** @var TableList */
	protected $tables;

	/**
	 * QueryGroup constructor.
	 * @param Table $root
	 * @param bool $primary
	 */
	public function __construct(Table $root, $primary = false)
	{
		$this->is_primary = $primary;
		$this->root = $root;
		$this->tables = new TableList();
		$this->addTable($root);
	}

	public function __debugInfo() {
		return [
			'root' => $this->root,
			'tables' => $this->tables,
		];
	}

	public function addTable(Table $table)
	{
		if ($this->isSubQuery()) {
			$table->setIsDescendant(true);
		}
		$this->tables->addTable($table);
	}

	public function isPrimary()
	{
		return $this->is_primary;
	}

	public function isSubQuery()
	{
		return !$this->is_primary;
	}

	public function hasTable(Table $table)
	{
		return $this->tables->hasTable($table);
	}

	/**
	 * @return Table
	 */
	public function getRoot()
	{
		return $this->root;
	}

	/**
	 * @param $table_alias
	 * @return Table
	 */
	public function getTable($table_alias)
	{
		return $this->tables->getTable($table_alias);
	}

	public function getReportFields()
	{
		$report_fields = [];
		foreach ($this->tables as $table) {
			$report_fields = array_merge($report_fields, $table->getReportFields());
		}
		return $report_fields;
	}

	public function getReportFilters()
	{
		if ($this->isSubQuery()) {
			return [];
		}
		$report_filters = [];
		foreach ($this->tables as $table) {
			foreach ($table->getFields() as $db_field) {
				if ($db_field->useAsFilter()) {
					$report_filters[] = new Filter($table, $db_field);
				}
			}
		}
		return $report_filters;
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @param SelectedFilter[] $selected_filters
	 * @return TableList
	 */
	public function requiredTables($selected_fields, $selected_filters)
	{
		$field_tables = $this->fieldsRequiredTables($selected_fields);

		if ($field_tables->count() == 0) {
			return $field_tables;
		}

		$filter_tables = $this->filtersRequiredTables($selected_filters);

		$field_tables->merge($filter_tables);

		return $field_tables;
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @param SelectedFilter[] $selected_filters
	 * @return TableList
	 */
	public function primaryQueryTables($selected_fields, $selected_filters)
	{
		$tables = new TableList($this->root);
		$field_tables = $this->fieldsRequiredTables($selected_fields);
		$tables->merge($field_tables);

		$filter_tables = $this->filtersRequiredTables($selected_filters);
		$tables->merge($filter_tables);

		return $tables;
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @return TableList
	 */
	public function subQueryTables($selected_fields)
	{
		return $this->fieldsRequiredTables($selected_fields);
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @return TableList
	 */
	public function fieldsRequiredTables($selected_fields)
	{
		$table_list = new TableList();
		foreach ($selected_fields as $field) {
			if ($this->tables->hasAlias($field->table())) {
				$table = $this->tables->getTable($field->table());
				$table_list->merge($table->getPath());
			}
		}

		return $table_list;
	}


	/**
	 * @param SelectedFilter[] $selected_filters
	 * @return TableList
	 */
	public function filtersRequiredTables($selected_filters)
	{
		$table_list = new TableList();
		foreach ($selected_filters as $filter) {
			if ($this->tables->hasAlias($filter->table())) {
				$table = $this->tables->getTable($filter->table());
				$table_list->merge($table->getPath());
			}
		}

		return $table_list;
	}

	/**
	 * @param SelectedField[] $selected_fields
	 * @return SelectedField[]
	 */
	public function applicableFields($selected_fields)
	{
		$applicable_fields = [];

		if ($this->isPrimary()) {
			return $selected_fields;
		}

		$applicable_fields[] = $this->subQueryRequiredFields();

		foreach ($selected_fields as $field) {
			if ($this->tables->hasAlias($field->table())) {
				$applicable_fields[] = $field;
			}
		}
		return $applicable_fields;
	}

	public function prefix()
	{
		if ($this->isSubQuery()) {
			return $this->root->aggregateName();
		}
		return '';
	}

	protected function subQueryRequiredFields()
	{
		$root_table = $this->root;

		$base_table = $root_table->getPath()->first();

		return new SelectedField($base_table->primary_key(), new Standard());

	}


}