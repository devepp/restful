<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;


class Where
{
	private $field;
	private $operator;
	private $value;

	/**
	 * Where constructor.
	 * @param $field
	 * @param $operator
	 * @param $value
	 */
	public function __construct($field, $operator, $value)
	{
		$this->field = trim($field);
		$this->operator = trim($operator);
		$this->value = $value;
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
	public function getStatementExpression()
	{
		return $this->getField().' '.$this->getOperator().' ';
	}

	/**
	 * @return string
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @return string
	 */
	public function getOperator()
	{
		return $this->operator;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return array
	 */
	public function getParameter()
	{
		return [
			$this->getParameterKey() => $this->getValue()
		];
	}

	/**
	 * @return mixed
	 */
	public function getParameterKey()
	{
		return ':'.str_replace('.', '__', $this->field);
	}
}