<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:55 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;

class Max extends AbstractSelectable
{
	const ID = 'Highest';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Max)';
	}

	public function fieldSql(DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'Max(`'.$field->tableAlias().'`.`'.$field->name().'`)';
		}
		return '`'.$field->tableAggregateAlias().'`.`'.$this->fieldAlias($field, true).'`';
	}

	public function fieldAlias(DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias().'_max';
		}
		return $field->tableAggregateAlias().'_'.$this->fieldAlias($field, true);
	}
}