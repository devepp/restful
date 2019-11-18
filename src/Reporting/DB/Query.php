<?php

namespace App\Reporting\DB;

use App\Reporting\DB\QueryBuilder\Builders\Delete;
use App\Reporting\DB\QueryBuilder\Builders\Insert;
use App\Reporting\DB\QueryBuilder\Builders\Select;
use App\Reporting\DB\QueryBuilder\Builders\Update;

class Query
{
	private $statement;

	private $parameters = [];

	/**
	 * Query constructor.
	 * @param $statement
	 * @param array $parameters
	 */
	public function __construct($statement, array $parameters = [])
	{
		$this->statement = $statement;
		$this->parameters = $parameters;
	}

	/**
	 * @return mixed
	 */
	public function getStatement()
	{
		return $this->statement;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param $tableExpression
	 * @return Select
	 */
	public static function selectQueryBuilder($tableExpression)
	{
		return new Select($tableExpression);
	}

	/**
	 * @param $tableExpression
	 * @return Update
	 */
	public static function updateQueryBuilder($tableExpression)
	{
		return new Update($tableExpression);
	}

	/**
	 * @param $tableExpression
	 * @return Insert
	 */
	public static function insertQueryBuilder($tableExpression)
	{
		return new Insert($tableExpression);
	}

	/**
	 * @param $tableExpression
	 * @return Delete
	 */
	public static function deleteQueryBuilder($tableExpression)
	{
		return new Delete($tableExpression);
	}

}