<?php

namespace App\Reporting\Processing;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\Filters\Filter;
use App\Reporting\ReportField;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Selectables\Standard;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;

class QueryGroup
{
	/** @var Table */
	protected $root;
	/** @var TableCollection */
	protected $tables;
	/** @var QueryGroup[] */
	protected $subQueryGroups;

	/**
	 * QueryGroup constructor.
	 * @param Table $root
	 * @param TableCollection $allTables
	 * @param QueryGroup[] $subQueryGroups
	 */
	public function __construct(Table $root, TableCollection $allTables, $subQueryGroups = [])
	{
		$this->root = $root;
		$this->tables = $allTables;
		$this->subQueryGroups = $subQueryGroups;
	}

	/**
	 * @param QueryBuilderFactoryInterface $queryBuilder
	 * @param SelectionsInterface $selections
	 * @return SelectQueryBuilderInterface
	 */
	public function getQuery(QueryBuilderFactoryInterface $queryBuilder, SelectionsInterface $selections)
	{
		$qb = $queryBuilder->selectFrom($this->root->name().' '.$this->root->alias());

		foreach ($this->tables as $table) {
			if ($table->alias() != $this->root->alias()) {
				$qb = $qb->join($table, '', 'left');
			}
		}

		foreach ($this->subQueryGroups as $queryGroup) {
			$qb = $qb->joinSubQuery($queryGroup->getQuery($queryBuilder, $selections), $queryGroup->root->alias(), '', 'left');
		}

		/** @var SelectedField $field */
		foreach ($selections->selectedFields() as $field) {
			$qb = $field->fieldSql($qb, false);
		}

		/** @var SelectedFilter $filter */
		foreach ($selections->selectedFilters() as $filter) {
			$qb = $filter->filterSql($qb);
		}

//		foreach ($this->)

		return $qb;
	}

	public function joinAsSubQuery(QueryBuilderFactoryInterface $queryBuilder, SelectionsInterface $selections)
	{

	}

	public function __debugInfo()
	{
		return [
			'root' => $this->root->alias(),
			'tables' => implode(', ', $this->tables->map(function (Table $table) {
				return $table->alias();
			})),
			'subQueryGroups' => \array_map(function ($queryGroup){
				return $queryGroup->__debugInfo();
			}, $this->subQueryGroups)
		];
	}















	public function addTable(Table $table)
	{
		$this->tables->addTable($table);
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
	 * @return TableCollection
	 */
	public function requiredTables($selected_fields, $selected_filters)
	{
		$field_tables = $this->fieldsRequiredTables($selected_fields);

		if ($field_tables->count() == 0) {
			return $field_tables;
		}

		$filter_tables = $this->filtersRequiredTables($selected_filters);

		$field_tables = $field_tables->merge($filter_tables);

		return $field_tables;
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @param SelectedFilter[] $selected_filters
	 * @return TableCollection
	 */
	public function primaryQueryTables($selected_fields, $selected_filters)
	{
		$tables = new TableCollection($this->root);
		$field_tables = $this->fieldsRequiredTables($selected_fields);
		$tables = $tables->merge($field_tables);

		$filter_tables = $this->filtersRequiredTables($selected_filters);
		$tables = $tables->merge($filter_tables);

		return $tables;
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @return TableCollection
	 */
	public function subQueryTables($selected_fields)
	{
		return $this->fieldsRequiredTables($selected_fields);
	}


	/**
	 * @param SelectedField[] $selected_fields
	 * @return TableCollection
	 */
	public function fieldsRequiredTables($selected_fields)
	{
		$table_list = new TableCollection();
		foreach ($selected_fields as $field) {
			if ($this->tables->hasAlias($field->table())) {
				$table = $this->tables->getTable($field->table());
				$table_list = $table_list->merge($table->getPath());
			}
		}

		return $table_list;
	}


	/**
	 * @param SelectedFilter[] $selected_filters
	 * @return TableCollection
	 */
	public function filtersRequiredTables($selected_filters)
	{
		$table_list = new TableCollection();
		foreach ($selected_filters as $filter) {
			if ($this->tables->hasAlias($filter->table())) {
				$table = $this->tables->getTable($filter->table());
				$table_list = $table_list->merge($table->getPath());
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

	/**
	 * @param SelectedField[] $selectedFields
	 * @return QueryField[]
	 */
	public function queryFields($selectedFields)
	{
		$queryFields = [];

		foreach ($selectedFields as $selectedField) {
			$fromQueryGroup = $this->tables->hasAlias($selectedField->table());


			if ($this->isPrimary()) {
				if ($fromQueryGroup) {

				}
				$queryFields[] = $selectedField;
			} else {

			}

		}



		if ($this->isPrimary()) {
			return $queryFields;
		}

		$applicable_fields[] = $this->subQueryRequiredFields();

		foreach ($selectedFields as $field) {
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

		return new SelectedField($base_table->primaryKey(), new Standard());

	}


}