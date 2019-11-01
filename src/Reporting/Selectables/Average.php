<?php

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Average extends AbstractSelectable
{
	const ID = 'Average';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Average)';
	}

	public function selectField(string $field)
	{
		return 'AVG('.$field.')';
	}

	public function alias(string $alias)
	{
		return $alias.'__average';
	}
}