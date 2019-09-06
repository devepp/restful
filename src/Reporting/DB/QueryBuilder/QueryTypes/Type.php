<?php

namespace App\Reporting\DB\QueryBuilder\QueryTypes;

use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;

abstract class Type
{
	const SELECT = 'SELECT';
	const UPDATE = 'UPDATE';
	const INSERT = 'INSERT';
	const DELETE = 'DELETE';

	private $type;

	public static function select()
	{
		return new Select(self::SELECT);
	}

	public static function update()
	{
		return new Update(self::UPDATE);
	}

	public static function insert()
	{
		return new Insert(self::INSERT);
	}

	public static function delete()
	{
		return new Delete(self::DELETE);
	}


	abstract public function type();

	/**
	 * @param TableExpression $tableExpression
	 * @param array $selectFields
	 * @param array $joinExpressions
	 * @param array $whereExpressions
	 * @return string
	 */
	abstract public function compileStatement(TableExpression $tableExpression, $selectFields = [], $joinExpressions = [], $whereExpressions = [], $groupBys = [], $havings = [], $orderBys = []);

		/**
	 * QueryType constructor.
	 * @param $type
	 */
	protected function __construct($type)
	{
		$this->type = $type;
	}
}