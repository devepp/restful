<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\Query;

class CIQueryBuilderInterface implements QueryBuilderInterface, SelectQueryBuilderInterface
{
	private $db;

	/**
	 * CIQueryBuilderInterface constructor.
	 * @param $db
	 */
	private function __construct($ci_db, $tableExpression)
	{
		$this->db = $ci_db;
		$this->db->from($tableExpression);
	}

	/**
	 * @return string
	 */
	public function getStatementExpression()
	{
		return $this->db->compile_select();
	}

	public function getParameters()
	{
		return [];
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	public function groupBy($field)
	{
		// TODO: Implement groupBy() method.
	}

	public function orderBy($field, $direction = 'ASC')
	{
		// TODO: Implement orderBy() method.
	}

	public function limit($limit, $offset = null)
	{
		// TODO: Implement limit() method.
	}

	public function subQuery($tableExpression)
	{
		// TODO: Implement subQuery() method.
	}


	public function getQuery()
	{
		return new Query($this->getStatementExpression(), $this->getParameters());
	}

	public function select(...$fieldExpressions)
	{
		foreach ($fieldExpressions as $field) {
			$this->db->select($fieldExpressions);
		}
	}

	public function selectSubQuery(SelectQueryBuilderInterface $queryBuilder, $alias)
	{
		// TODO: Implement selectSubQuery() method.
	}

	public function where($field, $operator, $value)
	{
		if ($operator) {
			$this->db->where($field . ' ' . $operator, $value);
		} else {
			$this->db->where($field, $value);
		}
	}

	public function whereRaw($whereString)
	{
		$this->db->where($whereString);
	}

	public function orWhere($field, $operator, $value)
	{
		$this->db->or_where($field.' '.$operator, $value);
	}

	public function whereNotIn($field, $values)
	{
		// TODO: Implement whereNotIn() method.
	}

	public function whereNotNull($field)
	{
		// TODO: Implement whereNotNull() method.
	}

	public function whereExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		// TODO: Implement whereExists() method.
	}

	public function whereNotExists(SelectQueryBuilderInterface $selectQueryBuilder)
	{
		// TODO: Implement whereNotExists() method.
	}

	public function whereIn($field, $values)
	{
		$this->db->where_in($field, $values);
	}

	public function whereNull($field)
	{
		$this->db->where($field.' IS NULL');
	}

	public function join($table, $on, $type = 'inner')
	{
		$this->db->join($table, $on, $type);
	}

	public function joinSubQuery(SelectQueryBuilderInterface $subQuery, $alias, $on, $type = 'inner')
	{
		// TODO: Implement joinSubQuery() method.
	}
}