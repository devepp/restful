<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

class TableExpression implements SqlExpressionInterface
{
	private $table;
	private $alias;

	public static function fromString($tableExpression)
	{
		if (\is_object($tableExpression)) {
			if(method_exists($tableExpression, '__toString')) {
				$tableExpression = $tableExpression->__toString();
			} else {
				throw new \InvalidArgumentException('$tableExpression must either be a string or implement a __toString() method');
			}
		}

		$tableExpression = trim($tableExpression);

		if (substr_count($tableExpression, ' ') > 1) {
			throw new \InvalidArgumentException('$tableExpression cannot contain more than 1 space');
		}

		$spaceIndex = strpos($tableExpression,' ');

		$table = $spaceIndex === false ? $tableExpression : substr($tableExpression, 0, $spaceIndex);
		$alias = $spaceIndex === false ? null : substr($tableExpression, $spaceIndex + 1);

		return new self($table, $alias);
	}

	/**
	 * TableExpression constructor.
	 * @param $table
	 * @param null $alias
	 */
	public function __construct($table, $alias = null)
	{
		$this->table = $table;
		$this->alias = $alias;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getStatementExpression();
	}

	/**
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return null|string
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @return null|string
	 */
	public function getAliasOrTable()
	{
		if ($this->alias) {
			return $this->alias;
		}
		return $this->table;
	}

	public function getStatementExpression()
	{
		if ($this->alias) {
			return $this->table.' '.$this->alias;
		}

		return $this->table;
	}

	public function getParameters()
	{
		return [];
	}


	private function parseTableFromExpression($tableExpression)
	{
		if (strpos($tableExpression,' ') === false) {
			return $tableExpression;
		}

		return substr($tableExpression, 0, strpos($tableExpression, ' '));
	}

	private function parseAliasFromExpression($tableExpression)
	{
		if (strpos($tableExpression,' ') === false) {
			return null;
		}

		return substr($tableExpression, strpos($tableExpression, ' ') + 1);
	}


}