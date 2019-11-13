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

class Percent extends AbstractSelectable
{
	const ID = 'Total';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Percent)';
	}

	public function selectField($field)
	{
		return 'SUM(IF('.$field.' != 0, 1, 1))/COUNT('.$field.')';
	}

	public function alias($alias)
	{
		return $alias.'__percent';
	}
}