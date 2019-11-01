<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 10:05 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Min extends AbstractSelectable
{
	const ID = 'Lowest';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Min)';
	}

	public function selectField(string $field)
	{
		return 'MIN('.$field.')';
	}

	public function alias(string $alias)
	{
		return $alias.'__min';
	}


}