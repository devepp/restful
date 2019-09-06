<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\Query;
use App\Reporting\DB\QueryBuilder\QueryParts\From;
use App\Reporting\DB\QueryBuilder\QueryParts\Join;
use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\QueryParts\Where;
use App\Reporting\DB\QueryBuilder\QueryTypes\Type;
use App\Reporting\DB\QueryBuilder as QBInterface;

class QueryBuilder implements QBInterface
{
	/** @var Type */
	protected $type;

	protected $select = [];

	/** @var TableExpression */
	protected $from;

	protected $where = [];

	protected $joins = [];

	protected $parameters = [];

	public static function selectBuilder($tableExpression)
	{
		$qb = new self($tableExpression, Type::select());
		return $qb;
	}

	public static function updateBuilder($tableExpression)
	{
		$qb = new self($tableExpression, Type::update());
		return $qb;
	}

	public static function insertBuilder($tableExpression)
	{
		$qb = new self($tableExpression, Type::insert());
		return $qb;
	}

	public static function deleteBuilder($tableExpression)
	{
		$qb = new self($tableExpression, Type::delete());
		return $qb;
	}

	/**
	 * QueryBuilder constructor.
	 * @param $tableExpression
	 * @param Type|null $type
	 */
	public function __construct($tableExpression, Type $type = null)
	{
		$this->from = new TableExpression($tableExpression);
		$this->type = $type ? $type : Type::select();
	}

	public function getQuery()
	{
		return new Query($this->getStatement(), $this->getParameters());
	}

	public function where($field, $operator, $value)
	{
		$this->where = [new Where($field, $operator, $value)];
		return $this;
	}

	public function whereRaw($whereString)
	{
		//TODO where raw
//		$this->where = [new Where($field, $operator, $value)];
//		return $this;
	}

	public function andWhere($field, $operator, $value)
	{
		$this->where[] = new Where($field, $operator, $value);
		return $this;
	}

	public function whereIn($field, $values)
	{
		//TODO - make where in object
//		$this->where[] = new Where($field, $operator, $value);
//		return $this;
	}

	public function whereNull($field)
	{
		$this->where[] = new Where($field, ' IS NULL');
		return $this;
	}

	public function join($table, $on, $type = 'inner')
	{
		$this->joins[] = new Join($table, $on, $type);
		return $this;
	}

	public function delete($tableExpression = null)
	{
		$this->type = Type::delete();
		$this->from = new TableExpression($tableExpression);
	}

	public function update($tableExpression = null)
	{
		$this->type = Type::update();
		$this->from = new TableExpression($tableExpression);
	}

	public function insert($tableExpression = null)
	{
		$this->type = Type::insert();
		$this->from = new TableExpression($tableExpression);
	}

	public function select(...$fieldExpresssions)
	{
		$this->select = $fieldExpresssions;
	}

	public function addSelect(...$fieldExpresssions)
	{
		$this->select = $this->select + $fieldExpresssions;
	}

	protected function getStatement()
	{
		return $this->type->compileStatement($this->from, $this->select, $this->joins, $this->where);
	}

	protected function getParameters()
	{
		return $this->parameters;
	}


}