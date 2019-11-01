<?php
/**
 * Created by PhpStorm.
 * User: Paul.Epp
 * Date: 1/8/2019
 * Time: 9:56 AM
 */

namespace App\Reporting\Selectables;

use App\Reporting\DatabaseFields\DatabaseField;
use App\Reporting\Resources\Table;

class Custom extends AbstractSelectable
{
	const ID = 'Custom';

	public function defaultLabel(DatabaseField $field)
	{
		return ucwords(str_replace('_', ' ', $field->name())).' (Custom)';
	}

	public function selectField(string $field)
	{
		// TODO: Implement alias() method.
	}

	public function alias(string $alias)
	{
		// TODO: Implement alias() method.
	}


}