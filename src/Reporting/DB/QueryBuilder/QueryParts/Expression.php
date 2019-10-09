<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

class Expression implements SqlExpressionInterface
{
	/** @var string */
	private $expression;

	/** @var array */
	private $parameters;

	/**
	 * Expression constructor.
	 * @param $expression
	 * @param array $parameters
	 */
	public function __construct($expression, $parameters = [])
	{
		$this->expression = $expression;
		$this->parameters = $parameters;
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	public function getStatementExpression()
	{
		return $this->expression;
	}

	public function getParameters()
	{
		return $this->parameters;
	}
}