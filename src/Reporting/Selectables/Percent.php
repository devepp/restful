<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:55 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;

class Percent extends AbstractSelectable
{
	const ID = 'Total';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Percent)';
	}

	public function fieldSql(DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'SUM(IF(`'.$field->tableAlias().'`.`'.$field->name().'` != 0, 1, 1))/COUNT(`'.$field->tableAlias().'`.`'.$field->name().'`)';
		}
		return '`'.$field->tableAggregateAlias().'`.`'.$this->fieldAlias($field, true).'`';
	}

	public function fieldAlias(DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias().'_percent';
		}
		return $field->tableAggregateAlias().'_'.$this->fieldAlias($field, true);
	}
}