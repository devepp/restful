<?php

namespace App\Reporting\DB\Schema;

interface TableInterface
{
	/**
	 * @param $fieldName
	 * @return FieldInterface
	 */
	public function field($fieldName);

	/**
	 * @param $fieldName
	 * @return bool
	 */
	public function hasField($fieldName);

	/**
	 * @return string
	 */
	public function name();
}