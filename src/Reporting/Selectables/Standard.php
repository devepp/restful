<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:52 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;

class Standard extends AbstractSelectable
{
	const ID = 'Default';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name()));
	}

	public function fieldSql(DatabaseField $field, $subQueryGroup)
	{
		return '`'.$field->tableAlias().'`.`'.$field->name().'`';
	}

	public function fieldAlias(DatabaseField $field, $subQueryGroup)
	{
		return $field->alias();
	}
}