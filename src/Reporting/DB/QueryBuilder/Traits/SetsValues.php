<?php

namespace App\Reporting\DB\QueryBuilder\Traits;

use App\Reporting\DB\QueryBuilder\QueryParts\SetAssignment;

trait SetsValues
{
	protected $values = [];


	public function setValue($fieldName, $value)
	{
		$clone = clone $this;

		$clone->values[$fieldName] = $value;

		return $clone;
	}

	public function setValues($values)
	{
		$clone = clone $this;

		foreach ($values as $field => $value) {
			$clone = $this->setValue($field, $value);
		}

		return $clone;
	}

	protected function setStatementExpression()
	{
		if (empty($this->values)) {
			return '';
		}

		$assignments = [];

		foreach ($this->values as $field => $value) {
			$assignments[] = new SetAssignment($field, $value);
		}

		return ' SET '.implode(', ', $assignments);
	}

	protected function getSetParameters()
	{
		$parameters = [];

		foreach ($this->values as $value) {
			$parameters = $parameters + [$value];
		}

		return $parameters;
	}
}