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

class Count extends AbstractSelectable
{
	const ID = 'Count';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Count)';
	}

	public function selectField($field)
	{
		return 'COUNT('.$field.')';
	}

	public function alias($alias)
	{
		return $alias.'__count';
	}
}