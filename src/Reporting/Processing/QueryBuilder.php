<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/17/2019
 * Time: 10:38 AM
 */

namespace App\Reporting\Processing;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\DatabaseFields\PrimaryKey;
use App\Reporting\Resources\Limit;
use App\Reporting\Resources\ReportConfig;
use App\Reporting\Resources\Table;
use App\Reporting\SelectedField;
use App\Reporting\SelectedFilter;
use App\Reporting\SelectionsInterface;

class QueryBuilder
{
	/** @var SelectionsInterface */
	protected $selections;

	/** @var QueryGroup[] */
	protected $sub_query_groups;

	/** @var QueryGroup */
	protected $primary_group;

	/**
	 * QueryBuilder constructor.
	 * @param SelectionsInterface $selections
	 * @param QueryGroup[] $query_groups
	 */
	public function __construct(SelectionsInterface $selections, $query_groups)
	{
		$this->selections = $selections;
		foreach ($query_groups as $group) {
			if ($group->isPrimary()) {
				$this->primary_group = $group;
			} else {
				$this->sub_query_groups[] = $group;
			}
		}
	}

	public function buildQuery()
	{
		$sub_queries = [];
		foreach ($this->sub_query_groups as $group) {
			$sub_query = $this->buildSqlSubQuery($group, $this->selections->selectedFields(), $this->selections->selectedFilters());
			if ($sub_query) {
//				echo '<pre>';
//				var_dump($group);
//				die(var_dump($group->prefix()));
				$sub_queries[$group->prefix()] = $sub_query;
			}
		}

		//build some of primary
		$query = $this->buildSqlPrimaryGroup($this->primary_group, $this->selections->selectedFields(), $this->selections->selectedFilters(), $sub_queries, $this->selections->limit());

//		var_dump($query);

		return $query;
	}

	/**
	 * @param QueryGroup $group
	 * @param SelectedField[] $selected_fields
	 * @param SelectedFilter[] $selected_filters
	 * @return string
	 */
	public function buildSqlPrimaryGroup(QueryGroup $group, $selected_fields, $selected_filters, $sub_queries, Limit $limit)
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
			$query .= $this->joinSubQuery($alias, $sub_query, $root_table->primary_key());
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
	public function buildSqlSubQuery(QueryGroup $group, $selected_fields, $selected_filters)
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
		$query .= $this->groupBySql($first_table->primary_key());


		return $query;
	}


	/**
	 * @param SelectedField[] $applicable_fields
	 * @param QueryGroup $subQueryGroup
	 * @return string
	 */
	protected function selectSql($applicable_fields, $subQueryGroup = null)
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
	protected function fromSql(Table $table)
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
	protected function joinSubQuery($sub_query_alias, $sub_query_sql, PrimaryKey $primary_key)
	{
		$sql = 'LEFT JOIN ('.$sub_query_sql.') AS '.$sub_query_alias.' ON `'.$sub_query_alias.'`.`'.$primary_key->alias().'` = `'.$primary_key->tableAlias().'`.`'.$primary_key->name().'` ';

		return $sql;
	}


	/**
	 * @param Table $table
	 * @param $tables_used
	 * @return string
	 */
	protected function joinSql(Table $table, $tables_used)
	{
		$sql = ' '.$table->joinSql($tables_used);

		return $sql;
	}


	/**
	 * @param SelectedFilter[] $selected_filters
	 * @return string
	 */
	protected function whereSql($selected_filters, $subQuery = false)
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
	protected function groupBySql(DatabaseField $field)
	{
		$sql = ' GROUP BY '.$field->tableAlias().'.'.$field->name();

		return $sql;
	}





}