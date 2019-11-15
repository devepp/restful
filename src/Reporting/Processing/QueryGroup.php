<?php

namespace App\Reporting\Processing;

use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\FieldInterface;
use App\Reporting\FilterInterface;
use App\Reporting\Request\Limit;
use App\Reporting\Resources\Table;
use App\Reporting\Resources\TableCollection;
use App\Reporting\Resources\TableCollectionFunctions\Filters\Filter;
use App\Reporting\Resources\TableCollectionFunctions\Maps\Map;
use App\Reporting\Resources\TableCollectionFunctions\Sorts\Sort;
use App\Reporting\SelectedFieldCollection;
use App\Reporting\SelectedFilterCollection;

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
	 * @param SelectedFieldCollection $fields
	 * @param SelectedFilterCollection $filters
	 * @param Limit|null $limit
	 * @param array $groupings
	 * @param array $sorts
	 * @return SelectQueryBuilderInterface
	 */
	public function getQuery(QueryBuilderFactoryInterface $queryBuilder, SelectedFieldCollection $fields, SelectedFilterCollection $filters, Limit $limit = null, $groupings = [], $sorts = [])
	{
		$fromTable = $this->fromTable();

		$qb = $queryBuilder->selectFrom($fromTable->name().' '.$fromTable->alias());
		$qb = $this->joinTables($qb);
		$qb = $this->joinSubQueryGroups($qb, $queryBuilder, $fields, $filters);

		/** @var FieldInterface $field */
		foreach ($this->applicableFields($fields) as $field) {
			$qb = $field->addToQuery($qb);
		}
		foreach ($this->subQueryGroups as $queryGroup) {
			$qb = $queryGroup->selectFieldsForOuterQuery($qb, $fields);
		}

		/** @var FilterInterface $filter */
		foreach ($filters as $filter) {
			$qb = $filter->filterQuery($qb);
		}

		foreach ($groupings as $grouping) {
			$qb = $qb->groupBy($grouping);
		}

		foreach ($sorts as $sort) {
			$qb = $qb->orderBy($sort);
		}

		if ($limit) {
			$qb = $limit->appendToQuery($qb);
		}

		return $qb;
	}

	public function alias()
	{
		return $this->root->alias().'_aggregate';
	}

	public function joinCondition()
	{
		return $this->alias().'.'.$this->fromPrimaryKeyAlias().' = '.$this->fromTable()->alias().'.'.$this->fromPrimaryKeyName();
	}

	/**
	 * @param SelectQueryBuilderInterface $queryBuilder
	 * @param SelectedFieldCollection $selectedFields
	 * @return SelectQueryBuilderInterface
	 */
	public function selectFieldsForOuterQuery(SelectQueryBuilderInterface $queryBuilder, SelectedFieldCollection $selectedFields)
	{
		$applicableFields = $this->applicableFields($selectedFields);

		foreach ($applicableFields as $field) {
			$queryBuilder = $field->addToQueryAsAggregate($queryBuilder, $this->alias());
		}

		return $queryBuilder;
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
	 * @param SelectedFieldCollection $fields
	 * @param SelectedFilterCollection $filters
	 * @return SelectQueryBuilderInterface
	 */
	private function joinSubQueryGroups(SelectQueryBuilderInterface $queryBuilder, QueryBuilderFactoryInterface $queryBuilderFactory, SelectedFieldCollection $fields, SelectedFilterCollection $filters)
	{
		foreach ($this->subQueryGroups as $queryGroup) {
			$subQuery = $queryGroup->getQuery($queryBuilderFactory, $fields, $filters);
			$primaryKeySelect = '`'.$this->fromTable()->alias().'`.`'.$this->fromTable()->primaryKey()->name().'` '.$this->fromPrimaryKeyAlias();

			$subQuery = $subQuery->select($primaryKeySelect);

			$queryBuilder = $queryBuilder->joinSubQuery($subQuery, $queryGroup->alias(), $queryGroup->joinCondition(), 'left');
		}

		return $queryBuilder;
	}

	/**
	 * @param SelectedFieldCollection $fields
	 * @return SelectedFieldCollection
	 */
	private function applicableFields(SelectedFieldCollection $fields)
	{
		$applicableFields = new SelectedFieldCollection();

		foreach ($fields as $field) {
			if ($this->fieldApplicable($field, $this->nodeTables)) {
				$applicableFields = $applicableFields->withField($field);
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

	private function tablesToJoin()
	{
		$tables = $this->pathTables->merge($this->nodeTables);

		$sortedTables = $tables->sort(Sort::byDistanceTo($this->fromTable(), $tables));

		return $sortedTables->filter(Filter::excludeTable($this->fromTable()));
	}

	private function firstRelatedTable(Table $tableToFindRelationshipsFor, TableCollection $possibleRelations)
	{
		$related = $possibleRelations->filter(Filter::byDirectRelationTo($tableToFindRelationshipsFor));

		if ($related->isEmpty()) {
			$tableAliases = implode(', ', $possibleRelations->map(Map::toAliases()));
			throw new \LogicException('Table '.$tableToFindRelationshipsFor->alias().' does not have any direct relationships with '.$tableAliases);
		}

		return $related->first();
	}

	private function fromTable()
	{
		return $this->pathTables->first();
	}

	private function fromPrimaryKeyName()
	{
		return $this->fromTable()->primaryKey()->name();
	}

	private function fromPrimaryKeyAlias()
	{
		return $this->fromTable()->alias().'__'.$this->fromPrimaryKeyName();
	}
}