<?php

namespace App\Reporting\DB;

interface QueryBuilder
{
	public static function selectBuilder($tableExpression);

	public static function updateBuilder($tableExpression);

	public static function insertBuilder($tableExpression);

	public static function deleteBuilder($tableExpression);

	public function getQuery();

	public function select(...$fieldExpressions);

	public function addSelect(...$fieldExpressions);

	public function where($field, $operator, $value);

	public function whereRaw($whereString);

	public function andWhere($field, $operator, $value);

	public function whereIn($field, $values);

	public function whereNull($field);

	public function join($table, $on, $type = 'inner');

	public function update($table = null);

	public function insert($table = null);

	public function delete($table = null);
}