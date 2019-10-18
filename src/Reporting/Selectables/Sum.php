<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:55 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Sum extends AbstractSelectable
{
	const ID = 'Total';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Sum)';
	}

	public function fieldSql(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'SUM(`'.$field->tableAlias().'`.`'.$field->name().'`)';
		}
		return '`'.$field->tableAggregateAlias().'`.`'.$this->fieldAlias($field, true).'`';
	}

	public function fieldAlias(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias().'_sum';
		}
		return $field->tableAggregateAlias().'_'.$this->fieldAlias($field, true);
	}
}