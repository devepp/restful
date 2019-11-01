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

	public function selectField(string $field)
	{
		return $field;
	}

	public function alias(string $alias)
	{
		return $alias;
	}


}