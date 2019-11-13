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

	public function selectField($field)
	{
		return 'GROUP_CONCAT('.$field.')';
	}

	public function alias($alias)
	{
		return $alias.'__list';
	}


}