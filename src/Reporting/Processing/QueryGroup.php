<?php

namespace App\Reporting\Processing;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\FieldInterface;
use App\Reporting\FilterInterface;
use App\Reporting\Filters\Filter;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Filters\Filter as TableFilter;
use App\Reporting\Resources\TableCollectionFunctions\Filters\DirectlyRelatedTo;
use App\Reporting\Resources\TableCollectionFunctions\Maps\Map;
use App\Reporting\Resources\TableCollectionFunctions\Sorts\Sort;
use App\Reporting\Selectables\Standard;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;

class QueryGroup
{
	/** @var Table */
	protected $root;
	/** @var TableCollection */
	protected $nodeTables;
	/** @var TableCollection */
	protected $pathTables;
	/** @var QueryGroup[] */
	protected $subQueryGroups;

	/**
	 * QueryGroup constructor.
	 * @param Table $root
	 * @param TableCollection $nodeTables
	 * @param TableCollection $pathTables
	 * @param QueryGroup[] $subQueryGroups
	 */
	public function __construct(Table $root, TableCollection $nodeTables, TableCollection $pathTables, array $subQueryGroups = [])
	{
		$this->root = $root;
		$this->nodeTables = $nodeTables;
		$this->pathTables = $pathTables;
		$this->subQueryGroups = $subQueryGroups;
	}


	/**
	 * @param QueryBuilderFactoryInterface $queryBuilder
	 * @param SelectionsInterface $selections
	 * @param FieldInterface|null $groupBy
	 * @return SelectQueryBuilderInterface
	 */
	public function getQuery(QueryBuilderFactoryInterface $queryBuilder, SelectionsInterface $selections, DatabaseField $groupBy = null)
	{
		$fromTable = $this->fromTable();
		$qb = $queryBuilder->selectFrom($fromTable->name().' '.$fromTable->alias());

		$qb = $this->joinTables($qb);

		$qb = $this->joinSubQueryGroups($qb, $queryBuilder, $selections);

		/** @var FieldInterface $field */
		foreach ($this->applicableFields($selections->selectedFields()) as $field) {
			$qb = $field->addToQuery($qb);
		}

		/** @var FilterInterface $filter */
		foreach ($selections->selectedFilters() as $filter) {
			$qb = $filter->filterQuery($qb);
		}

//		foreach ($this->)

		return $qb;
	}

	public function joinAsSubQuery(SelectQueryBuilderInterface $queryBuilder, SelectionsInterface $selections, $joinToAlias, QueryBuilderFactoryInterface $qbFactory)
	{
		// TODO probably remove this.  not sure if its the best way. getQuery might be better

		return $queryBuilder->joinSubQuery($this->getQuery($qbFactory, $selections), $this->alias(), $this->joinCondition($joinToAlias), 'left');
	}

	public function alias()
	{
		return $this->root->alias();
	}

	public function joinCondition($tableAlias)
	{
		return $this->root->alias();
	}

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @return SelectQueryBuilderInterface
	 */
	private function joinTables(SelectQueryBuilderInterface $queryBuilder)
	{
		$joined = new TableCollection([$this->fromTable()]);

		foreach ($this->tablesToJoin() as $table) {
			$relatedTable = $this->firstRelatedTable($table, $joined);

			$queryBuilder = $queryBuilder->join($table, $table->joinCondition($relatedTable), 'left');

			$joined = $joined->addTable($table);
		}

		return $queryBuilder;
	}

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @param QueryBuilderFactoryInterface $queryBuilderFactory
	 * @param SelectionsInterface $selections
	 * @return SelectQueryBuilderInterface
	 */
	private function joinSubQueryGroups(SelectQueryBuilderInterface $queryBuilder, QueryBuilderFactoryInterface $queryBuilderFactory, SelectionsInterface $selections)
	{
		foreach ($this->subQueryGroups as $queryGroup) {
//			$subQuery = $queryGroup->getQuery($queryBuilderFactory, $selections, $this->fromTable()->primaryKey());
			$subQuery = $queryGroup->getQuery($queryBuilderFactory, $selections);
			$primaryKeySelect = '`'.$this->fromTable()->alias().'`.`'.$this->fromTable()->primaryKey()->name().'` AS '.$this->fromTable()->alias().'__'.$this->fromTable()->primaryKey()->name();

			$subQuery = $subQuery->select($primaryKeySelect);

			$queryBuilder = $queryBuilder->joinSubQuery($subQuery, $queryGroup->alias(), $queryGroup->joinCondition($this->root->alias()), 'left');
		}

		return $queryBuilder;
	}

	/**
	 * @param FieldInterface[] $selectedFields
	 * @return FieldInterface[]
	 */
	private function applicableFields($selectedFields)
	{
		$applicableFields = [];

		foreach ($selectedFields as $field) {
			if ($this->fieldApplicable($field, $this->nodeTables)) {
				$applicableFields[] = $field;
			}
		}
		return $applicableFields;
	}

	private function fieldApplicable(FieldInterface $field, TableCollection $tables)
	{
		foreach ($tables as $table) {
			if ($field->requiresTable($table)) {
				return true;
			}
		}

		return false;
	}

	private function fromTable()
	{
		return $this->pathTables->first();
	}

	private function tablesToJoin()
	{
		$tables = $this->pathTables->merge($this->nodeTables);

		$sortedTables = $tables->sort(Sort::byDistanceTo($this->fromTable(), $tables));

		return $sortedTables->filter(TableFilter::excludeTable($this->fromTable()));
	}

	private function firstRelatedTable(Table $tableToFindRelationshipsFor, TableCollection $possibleRelations)
	{
		$related = $possibleRelations->filter(TableFilter::byDirectRelationTo($tableToFindRelationshipsFor));

		if ($related->empty()) {
			$tableAliases = implode(', ', $possibleRelations->map(Map::toAliases()));
			throw new \LogicException('Table '.$tableToFindRelationshipsFor->alias().' does not have any direct relationships with '.$tableAliases);
		}

		return $related->first();
	}















	public function addTable(Table $table)
	{
		$this->tables = $this->tables->addTable($table);
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