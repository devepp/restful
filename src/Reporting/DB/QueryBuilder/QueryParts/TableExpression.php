<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

class TableExpression
{
	private $table;
	private $alias;

	/**
	 * TableExpression constructor.
	 * @param $table
	 */
	public function __construct($tableExpression)
	{
		$tableExpression = trim($tableExpression);

		if (substr_count($tableExpression, ' ') > 1) {
			throw new \InvalidArgumentException('$tableExpression cannot contain more than 1 space');
		}

		$this->table = $this->parseTableFromExpression($tableExpression);
		$this->alias = $this->parsealiasFromExpression($tableExpression);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		$aliasString = $this->alias ? ' '.$this->alias : '';
		return $this->table.$aliasString;
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