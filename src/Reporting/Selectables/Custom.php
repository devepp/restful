<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:56 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Custom extends AbstractSelectable
{
	const ID = 'Custom';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Custom)';
	}

	public function fieldSql(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'AVG(`'.$field->tableAlias().'`.`'.$field->name().'`)';
		}
		return '`'.$field->tableAggregateAlias().'`.`'.$this->fieldAlias($field, true).'`';
	}

	public function fieldAlias(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias().'_average';
		}
		return $field->tableAggregateAlias().'_'.$this->fieldAlias($field, true);
	}
}