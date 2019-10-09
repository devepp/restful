<?php

namespace App\Reporting\Processing;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DatabaseFields\PrimaryKey;
use App\Reporting\DB\QueryBuilder\SelectQueryBuilderInterface;
use App\Reporting\DB\QueryBuilderFactoryInterface;
use App\Reporting\Resources\Limit;
use App\Reporting\Resources\Table;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;
use InvalidArgumentException;

class QueryBuilder
{
	/** @var QueryBuilderFactoryInterface */
	private $db;

	/**
	 * QueryBuilder constructor.
	 * @param QueryBuilderFactoryInterface $queryBuilder
	 */
	public function __construct(QueryBuilderFactoryInterface $queryBuilder)
	{
		$this->db = $queryBuilder;

	}

	public function buildQuery(SelectionsInterface $selections, $queryGroups)
	{
		$primaryGroup = $this->primaryGroup($queryGroups);

		$qb = $this->db->selectFrom($primaryGroup->getRoot());

		foreach ($this->subQueryGroups($queryGroups) as $group) {
				$sub_query = $this->buildSqlSubQuery($group, $selections->selectedFields(), $selections->selectedFilters());
				if ($sub_query) {
					$sub_queries[$group->prefix()] = $sub_query;
				}

		}

		//build some of primary
		$query = $this->buildSqlPrimaryGroup($primaryGroup, $selections->selectedFields(), $selections->selectedFilters(), $sub_queries, $selections->limit());

//		var_dump($query);

		return $query;
	}

	/**
	 * @param QueryGroup $group
	 * @param SelectedField[] $selected_fields
	 * @param SelectedFilter[] $selected_filters
	 * @return string
	 */
	private function buildSqlPrimaryGroup(QueryGroup $group, $selected_fields, $selected_filters, $sub_queries, Limit $limit)
	{
		$required_tables = $group->primaryQueryTables($selected_fields, $selected_filters);

		$applicable_fields = $group->applicableFields($selected_fields);
		$query = $this->selectSql($applicable_fields);

		$tables_used = [];
		foreach ($required_tables as $table) {
			if (empty($tables_used)) {
				$query .= $this->fromSql($table);
				$tables_used[] = $table->alias();
			} else {
				$query .= $this->joinSql($table, $tables_used);
				$tables_used[] = $table->alias();
			}
		}
		$root_table = $group->getRoot();
		foreach ($sub_queries as $alias => $sub_query) {
			$query .= $this->joinSubQuery($alias, $sub_query, $root_table->primaryKey());
		}

		$query .= $this->whereSql($selected_filters);
		$query .= ' '.$limit->sql();

		return $query;
	}

	/**
	 * @param QueryGroup $group
	 * @param SelectedField[] $selected_fields
	 * @param SelectedFilter[] $selected_filters
	 * @return string
	 */
	private function buildSqlSubQuery(QueryGroup $group, $selected_fields, $selected_filters)
	{
		$required_tables = $group->subQueryTables($selected_fields);
		if ($required_tables->count() < 1) {
			return '';
		}

		$applicable_fields = $group->applicableFields($selected_fields);
		$query = $this->selectSql($applicable_fields, $group);

		$tables_used = [];
		foreach ($required_tables as $table) {
			if (empty($tables_used)) {
				$query .= $this->fromSql($table);
				$tables_used[] = $table->alias();
			} else {
				$query .= $this->joinSql($table, $tables_used);
				$tables_used[] = $table->alias();
			}
		}
		$query .= $this->whereSql($selected_filters, true);

		$first_table = $required_tables->first();
		$query .= $this->groupBySql($first_table->primaryKey());


		return $query;
	}


	/**
	 * @param SelectedField[] $applicable_fields
	 * @param QueryGroup $subQueryGroup
	 * @return string
	 */
	private function selectSql($applicable_fields, $subQueryGroup = null)
	{
		$sql = 'SELECT ';

		$fields = [];
		foreach ($applicable_fields as $field) {
			$fields[] = $field->fieldSql($subQueryGroup).' AS '.$field->fieldAlias($subQueryGroup);
		}
		$sql .= implode(', ', $fields);

		return $sql;
	}


	/**
	 * @param Table $table
	 * @return string
	 */
	private function fromSql(Table $table)
	{
		$sql = ' FROM `'.$table->name().'` `'.$table->alias().'`';

		return $sql;
	}


	/**
	 * @param $sub_query_alias
	 * @param $sub_query_sql
	 * @param PrimaryKey $primary_key
	 * @return string
	 */
	private function joinSubQuery($sub_query_alias, $sub_query_sql, PrimaryKey $primary_key)
	{
		$sql = 'LEFT JOIN ('.$sub_query_sql.') AS '.$sub_query_alias.' ON `'.$sub_query_alias.'`.`'.$primary_key->alias().'` = `'.$primary_key->tableAlias().'`.`'.$primary_key->name().'` ';

		return $sql;
	}


	/**
	 * @param Table $table
	 * @param $tables_used
	 * @return string
	 */
	private function joinSql(Table $table, $tables_used)
	{
		$sql = ' '.$table->joinSql($tables_used);

		return $sql;
	}


	/**
	 * @param SelectedFilter[] $selected_filters
	 * @return string
	 */
	private function whereSql($selected_filters, $subQuery = false)
	{
		if ($subQuery || count($selected_filters) == 0) {
			return '';
		}

		$filter_sql = [];
		foreach ($selected_filters as $filter) {
			$filter_sql[] = $filter->filterSql();
		}

		$sql = ' WHERE '.implode(' AND ', $filter_sql);

		return $sql;
	}


	/**
	 * @param DatabaseField $field
	 * @return string
	 */
	private function groupBySql(DatabaseField $field)
	{
		$sql = ' GROUP BY '.$field->tableAlias().'.'.$field->name();

		return $sql;
	}

	/**
	 * @param QueryGroup[] $queryGroups
	 * @return QueryGroup
	 */
	private function primaryGroup($queryGroups)
	{
		foreach ($queryGroups as $queryGroup) {
			if ($queryGroup->isPrimary()) {
				return $queryGroup;
			}
		}

		throw new InvalidArgumentException('At least one QueryGroup must be the primary QueryGroup. None given.');
	}

	/**
	 * @param QueryGroup[] $queryGroups
	 * @return QueryGroup[]
	 */
	private function subQueryGroups($queryGroups)
	{
		$primaries = 0;
		$subQueryGroups = [];
		foreach ($queryGroups as $queryGroup) {
			if ($queryGroup->isPrimary()) {
				$primaries++;
				if ($primaries > 1) {
					throw new InvalidArgumentException('No more than one primary QueryGroup should be used. More than 1 was given.');
				}
			} else {
				$subQueryGroups[] = $queryGroup;
			}
		}

		return $subQueryGroups;
	}





}