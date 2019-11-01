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

class ListSelectable extends AbstractSelectable
{
	const ID = 'List';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (List)';
	}

	public function fieldSql(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return 'GROUP_CONCAT(`'.$field->tableAlias().'`.`'.$field->name().'`)';
		}
		return '`'.$field->tableAggregateAlias().'`.`'.$this->fieldAlias($field, true).'`';
	}

	public function fieldAlias(Table $table, DatabaseField $field, $subQueryGroup)
	{
		if ($subQueryGroup) {
			return $field->alias().'_list';
		}
		return $field->tableAggregateAlias().'_'.$this->fieldAlias($field, true);
	}

	public function selectField(string $field, string $alias = null)
	{
		return 'GROUP_CONCAT('.$field.') AS '.$alias.'_list';
	}
}