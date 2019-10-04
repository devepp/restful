<?php

namespace App\Reporting\DB\QueryBuilder\QueryParts;

class WhereIn implements WhereInterface
{
	private $field;
	private $values;

	/**
	 * Where constructor.
	 * @param $field
	 * @param $values
	 */
	public function __construct($field, $values)
	{
		if (!is_array($values)) {
			throw new \InvalidArgumentException('values must be an array');
		}
		if (empty($values)) {
			throw new \InvalidArgumentException('values must not be empty');
		}
		$this->field = trim($field);
		$this->values = $values;
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
		return $this->getField().' IN ('.implode(',', array_fill(0, count($this->values), '?')).')';
	}

	/**
	 * @return string
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @return mixed
	 */
	public function getValues()
	{
		return $this->values;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->getValues();
	}
}