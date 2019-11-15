<?php

namespace App\Reporting\DB\QueryBuilder\BuilderInterfaceParts;

interface SetsValuesInterface
{

	/**
	 * @param $fieldName
	 * @param $value
	 * @return self
	 */
	public function setValue($fieldName, $value);

	/**
	 * @param $values
	 * @return self
	 */
	public function setValues($values);
}