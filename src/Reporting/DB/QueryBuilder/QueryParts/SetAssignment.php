<?php


namespace App\Reporting\DB\QueryBuilder\QueryParts;

use App\Reporting\DB\QueryBuilder\SqlExpressionInterface;

class SetAssignment implements SqlExpressionInterface
{
	private $field;
	private $value;

	/**
	 * SetAssignment constructor.
	 * @param $field
	 * @param $value
	 */
	public function __construct($field, $value)
	{
		$this->field = $field;
		$this->value = $value;
	}

	public function __toString()
	{
		return $this->getStatementExpression();
	}

	public function getStatementExpression()
	{
		return $this->field.' = ?';
	}

	public function getParameters()
	{
		return [$this->value];
	}

}