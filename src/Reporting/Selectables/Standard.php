<?php

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Standard extends AbstractSelectable
{
	const ID = 'Default';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name()));
	}

	public function fieldSql(Table $table, DatabaseField $field, $subQueryGroup)
	{
		return '`'.$table->alias().'`.`'.$field->name().'`';
	}

	public function fieldAlias(Table $table, DatabaseField $field, $subQueryGroup)
	{
		return $field->alias($table->alias());
	}

	public function selectField(string $field, string $alias = null)
	{
		if ($alias === null) {
			return $field;
		}

		return $field.' '.$alias;
	}
}