<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 10:05 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;

class Min extends AbstractSelectable
{
	const ID = 'Lowest';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Min)';
	}

	public function fieldSql(DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'MIN(`'.$field->tableAlias().'`.`'.$field->name().'`)';
		}
		return '`'.$field->tableAggregateAlias().'`.`'.$this->fieldAlias($field, true).'`';
	}

	public function fieldAlias(DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias().'_min';
		}
		return $field->tableAggregateAlias().'_'.$this->fieldAlias($field, true);
	}
}