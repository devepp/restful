<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:54 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Count extends AbstractSelectable
{
	const ID = 'Count';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Count)';
	}

	public function fieldSql(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'COUNT(`'.$table->alias().'`.`'.$field->name().'`)';
		}
		return '`'.$table->aggregateName().'`.`'.$this->fieldAlias($table, $field, true).'`';
	}

	public function fieldAlias(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias($table->alias()).'_count';
		}
		return $table->aggregateName().'_'.$this->fieldAlias($table, $field, true);
	}

	public function selectField(string $field, string $alias = null)
	{
		return 'COUNT('.$field.') AS '.$alias.'_count';
	}
}