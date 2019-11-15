<?php

namespace App\Reporting\DB\QueryBuilder;

use App\Reporting\DB\QueryBuilder\QueryParts\TableExpression;
use App\Reporting\DB\QueryBuilder\Traits\makesExpressions;
use App\Reporting\DB\QueryBuilder\Traits\SetsValues;

class Insert extends QueryBuilder implements InsertQueryBuilderInterface
{
	use SetsValues, makesExpressions;

	/** @var TableExpression */
	protected $insertTable;

	protected $parameters = [];

	/** @var SelectQueryBuilderInterface */
	private $selectQuery;

	/**
	 * QueryBuilderInterface constructor.
	 * @param $tableExpression
	 */
	public function __construct($tableExpression)
	{
		$this->insertTable = new TableExpression($tableExpression);
	}

	public function insertSubQuery(SelectQueryBuilderInterface $selectQuery)
	{
		$this->values = [];

		$this->selectQuery = $selectQuery;
	}

	public function getStatementExpression()
	{
		if ($this->selectQuery) {
			return 'INSERT INTO '.$this->insertTable.' '.$this->selectQuery->getQuery()->getStatement();
		}

		if (count($this->values)) {
			return 'INSERT INTO '.$this->insertTable.' '.$this->fieldsString().' '.$this->valuesString();
		}

		throw new \LogicException('No values set for Insert Statement');
	}

	public function getParameters()
	{
		if ($this->selectQuery) {
			return $this->selectQuery->getQuery()->getParameters();
		}

		if (count($this->values)) {
			return array_values($this->values);
		}

		throw new \LogicException('No values set for Insert Statement');
	}

	protected function fieldsString()
	{
		return '(' . implode(', ', array_keys($this->values)) . ')';
	}

	protected function valuesString()
	{
		$placeHolders = array_fill(0, count($this->values), '?');
		return 'VALUES (' . implode(',', $placeHolders) . ')';
	}
}