<?php

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Average extends AbstractSelectable
{
	const ID = 'Average';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Average)';
	}

	public function fieldSql(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'AVG(`'.$table->alias().'`.`'.$field->name().'`)';
		}
		return '`'.$table->aggregateName().'`.`'.$this->fieldAlias($table, $field, true).'`';
	}

	public function fieldAlias(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias($table->alias()).'_average';
		}
		return $table->aggregateName().'_'.$this->fieldAlias($table, $field, true);
	}
}