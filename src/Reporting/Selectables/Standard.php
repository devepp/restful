<?php

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Standard extends AbstractSelectable
{
	const ID = 'Default';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name()));
	}

	public function selectField($field)
	{
		return $field;
	}

	public function alias($alias)
	{
		return $alias;
	}


}