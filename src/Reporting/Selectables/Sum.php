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

	public function selectField($field)
	{
		return 'IFNULL(SUM('.$field.'), 0)';
	}

	public function alias($alias)
	{
		return $alias.'__sum';
	}
}